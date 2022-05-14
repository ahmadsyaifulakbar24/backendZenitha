<?php

namespace App\Http\Controllers\API\Transaction;

use App\Helpers\FileHelpers;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Resources\Transaction\TransactionDetailResource;
use App\Http\Resources\Transaction\TransactionResource;
use App\Models\Cart;
use App\Models\Payment;
use App\Models\ProductCombination;
use App\Models\Transaction;
use Carbon\Carbon;
use GrahamCampbell\ResultType\Success;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class TransactionController extends Controller
{
    public function get(Request $request)
    {
        $request->validate([
            'user_id' => ['nullable', 'exists:users,id'],
            'from_date' => ['nullable', 'date_format:Y-m-d'],
            'till_date' => ['nullable', 'date_format:Y-m-d'],
            'limit_page' => ['required', 'in:0,1'],
            'limit' => ['nullable', 'integer'],
            'status' => ['nullable', 'in:pending,paid_off,expired,sent,canceled,finish'],
            'payment_status' => ['nullable', 'in:paid,not_paid'],
            'invoice_number' => ['nullable', 'string']
        ]);
        $limit = $request->input('limit', 10);

        $transaction = Transaction::query();

        if($request->payment_status) {
            if($request->payment_status == 'paid') {
                $transaction->whereNotNull('paid_off_time');
            } else if($request->payment_status == 'not_paid') {
                $transaction->whereNull('paid_off_time');
            }
        }

        if($request->invoice_number) {
            $transaction->where('invoice_number', $request->invoice_number);
        }

        if($request->user_id) {
            $transaction->where('user_id', $request->user_id);
        }

        if($request->status) {
            $transaction->where('status', $request->status);
        }

        if($request->from_date) 
        {
            $transaction->where(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"), '>=', $request->from_date);
        }

        if($request->till_date) 
        {
            $transaction->where(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"), '<=', $request->till_date);
        }
        $transaction->orderBy('created_at', 'desc');
        $result = ($request->limit_page == 1) ? $transaction->paginate($limit) : $transaction->get();
        return ResponseFormatter::success(TransactionResource::collection($result)->response()->getData(true), 'success get transaction data');
    }

    public function search(Request $request)
    {
        $request->validate([
            'search' => ['required', 'string'],
            'user_id' => ['nullable', 'exists:users,id'],
            'limit' => ['nullable', 'integer']
        ]);
        $limit = $request->input('limit', 10);

        $transaction = Transaction::joinProduct()->where('product_name', 'like', '%'.$request->search.'%');
        if($request->user_id) {
            $transaction->where('user_id', $request->user_id);
        }
        
        $transaction_ids = $transaction->groupBy('id')->pluck('id')->toArray();
        $new_transaction = Transaction::whereIn('id', $transaction_ids)->limit($limit)->get();
        return ResponseFormatter::success(TransactionResource::collection($new_transaction), 'success search transaction');
    }

    public function show(Transaction $transaction)
    {
        return ResponseFormatter::success(new TransactionDetailResource($transaction));
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'type' => ['required', 'in:marketplace,store'],
            'marketplace_resi' => [
                Rule::requiredIf($request->type == 'marketplace'),
                'file'
            ],
            'payment_method' => ['required', 'in:cod,transfer,po'],
            'bank_name' => [
                Rule::RequiredIf($request->payment_method != 'cod'), 
                'string'
            ],
            'no_rek' => [
                Rule::RequiredIf($request->payment_method != 'cod'), 
                'string'
            ],
            'shipping_cost' => ['required', 'integer'],
            'shipping_discount' => ['required', 'integer'],
            'total_price' => ['required', 'integer'],
            'address' => ['required', 'string'],
            'expedition' => ['required', 'string'],
            'transaction_product' => ['required', 'array'],
            'transaction_product.*.product_slug' => [
                'required', 
                Rule::exists('product_combinations', 'product_slug')->where(function($query) {
                    return $query->where('status', 'active')->whereNull('deleted_at');
                })
            ],
            'transaction_product.*.image' => ['required', 'string'],
            'transaction_product.*.product_name' => ['required', 'string'],
            'transaction_product.*.discount_product' => ['required', 'integer'],
            'transaction_product.*.discount_group' => ['required', 'integer'],
            'transaction_product.*.discount_customer' => ['required', 'integer'],
            'transaction_product.*.price' => ['required', 'integer'],
            'transaction_product.*.description' => ['required', 'string'],
            'transaction_product.*.quantity' => ['required', 'integer'],
            'transaction_product.*.notes' => ['nullable', 'string'],
        ]);

        $result = DB::transaction(function () use ($request) {

            // create transaction table
            $input = $request->except(['marketplace_resi']);
            $input['invoice_number'] = Transaction::max('invoice_number') + 1;
            if($request->type == 'marketplace') {
                $input['marketplace_resi'] = FileHelpers::upload_file('resi', $request->marketplace_resi);
            }
            $input['status'] = 'pending';
            $total_payment = ($request->payment_method == 'po') ? 2 : 1;
            $input['total_payment'] = $total_payment;
            $transaction = Transaction::create($input);
            // end create transaction table

            // create transaction product table
            $transaction->transaction_product()->createMany($request->transaction_product);
            // end create transaction product table

            // create payment table
            $date = Carbon::now();
            $unique_code = rand(0,env("MAX_UNIQUE_CODE"));
            if($request->payment_method == 'po') {
                $po1 = $request->total_price * env('PO_PAYMENT') / 100;
                $input_payment = [
                    [
                        'unique_code' => $unique_code,
                        'total' => $po1 + $unique_code,
                        'expired_time' => ($request->payment_method == 'cod') ? null : $date->modify("+24 hours"),
                        'order_payment' => 1,
                        'status' => 'process',
                    ],
                    [
                        'unique_code' => null,
                        'total' => $request->total_price - $po1,
                        'expired_time' => null,
                        'order_payment' => 2,
                        'status' => 'pending',
                    ],
                ];
            } else {
                $input_payment = [
                    [
                        'unique_code' => $unique_code,
                        'total' => $request->total_price + $unique_code,
                        'expired_time' => ($request->payment_method == 'cod') ? null : $date->modify("+24 hours"),
                        'order_payment' => 1,
                        'status' => 'process',
                    ]
                ];
            }
            $transaction->payments()->createMany($input_payment);
            // end create payment table

            // update stock after chechout
            foreach ($request->transaction_product as $transaction_product) {
                $product_combination = ProductCombination::where('product_slug', $transaction_product['product_slug'])->first();
                $product_combination->update([
                    'stock' => $product_combination->stock - $transaction_product['quantity']
                ]);
                $product_slugs[] = $transaction_product['product_slug'];
            }
            // end update stock after chechout

            // delete cart after success checkout
            Cart::where('user_id', $request->user_id)->whereIn('product_slug', $product_slugs)->delete();
            // end delete cart after success checkout
            return $transaction;
        });
        return ResponseFormatter::success(new TransactionDetailResource($result), 'success create transaction data');
    }

    public function update_status(Request $request, Transaction $transaction)
    {
        $request->validate([
            'status' => ['required', 'in:pending,paid_off,expired,sent,canceled,finish']
        ]);
        if($request->status == 'paid_off') {
            $transaction->update([ 
                'status' => $request->status,
                'paid_off_time' => Carbon::now(),
            ]);
        } else {
            $transaction->update([ 'status' => $request->status ]);
        }
        return ResponseFormatter::success(new TransactionDetailResource($transaction), 'success update status transaction data');
    }

    public function update_resi(Request $request, Transaction $transaction)
    {
        $request->validate([
            'number_resi' => ['required', 'string']
        ]);
        $transaction->update(['number_resi' => $request->number_resi]);
        return ResponseFormatter::success(new TransactionDetailResource($transaction), 'success update number resi transaction data');
    }

    public function handle_moota(Request $request) {
        $secret = env('MOOTA_WEBHOOK');
        $moota_signature = $request->header('signature');
        $data = $request->json()->all();
        // $data = [
        //     [
        //        "account_number" => "12312412312",
        //        "date" => "2019-11-10 14:33:01",
        //        "description" => "TRSF E-BANKING CR 11/10 124123 MOOTA CO",
        //        "amount" => 100061,
        //        "type" => "CR",
        //        "balance" => 520000,
        //        "updated_at" => "2019-11-10 14 =>33 =>01",
        //        "created_at" => "2019-11-10 14 =>33 =>01",
        //        "mutation_id" => "IHBb97sba7d",
        //        "token" => "OASiuh(DYNb97"
        //     ]
        // ];
        $data_string = json_encode($data);
        $signature = hash_hmac('sha256', $data_string, $secret);        
        Log::info($data);
        Log::info($moota_signature);
        // if($data) {
        //     foreach ($data as $res) {
        //         // get payment
        //         $payment = Payment::where([['status', 'process'], ['total', $res['amount'], ['expired_time', '>=' , $res['date']]]])->first();

        //         if(!empty($payment)) {

        //             // update payment status from moota
        //             $payment->update([
        //                 'status' => 'paid_off',
        //                 'paid_off_time' => Carbon::now()
        //             ]);

        //             // cek payment if all paid off
        //             $cek_payment = Payment::where([
        //                 ['transaction_id', $payment->transaction_id],
        //                 ['status', '!=', 'paid_off']
        //             ])->count();

        //             if($cek_payment == 0) {
        //                 Transaction::find($payment->transaction_id)->update([
        //                     'status' => 'paid_off',
        //                     'paid_off_time' => Carbon::now()
        //                 ]);
        //             }
        //             // end cek payment if all paid off
        //             Log::notice("success update status payment data");
        //             return ResponseFormatter::success(null, "success update status payment data");
        //         } else {
        //             Log::error("failed update status payment data");
        //             return ResponseFormatter::error([
        //                 'message' => 'failed update status payment data'
        //             ], 'update payment failed', 422);
        //         }
        //     }
        // }

        if($signature == $moota_signature) {
            if($data) {
                foreach ($data as $res) {
                    // get payment
                    $payment = Payment::where([['status', 'process'], ['total', $res['amount'], ['expired_time', '>=' , $res['date']]]])->first();

                    if(!empty($payment)) {

                        // update payment status from moota
                        $payment->update([
                            'status' => 'paid_off',
                            'paid_off_time' => Carbon::now()
                        ]);

                        // cek payment if all paid off
                        $cek_payment = Payment::where([
                            ['transaction_id', $payment->transaction_id],
                            ['status', '!=', 'paid_off']
                        ])->count();

                        if($cek_payment == 0) {
                            Transaction::find($payment->transaction_id)->update([
                                'status' => 'paid_off',
                                'paid_off_time' => Carbon::now()
                            ]);
                        }
                        // end cek payment if all paid of
                        Log::notice("success update status payment data");
                        return ResponseFormatter::success(null, "success update status payment data");
                    } else {
                        Log::error("failed update status payment data");
                        return ResponseFormatter::error([
                            'message' => 'failed update status payment data'
                        ], 'update payment failed', 422);
                    }
                }
            }
        } else {
            Log::error('signature is invalid');
            return ResponseFormatter::error([
                'message' => 'signature is invalid'
            ], 'update payment failed', 422);
        }
    }
}
