<?php

namespace App\Http\Controllers\API\Transaction;

use App\Helpers\FileHelpers;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Resources\Transaction\TransactionDetailResource;
use App\Http\Resources\Transaction\TransactionResource;
use App\Models\Cart;
use App\Models\ProductCombination;
use App\Models\Transaction;
use Carbon\Carbon;
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
            'invoice_number' => ['nullable', 'string']
        ]);
        $limit = $request->input('limit', 10);

        // // update status expired
        // $expired_transaction = Transaction::where([['status', 'pending'], ['expired_time', '<=', Carbon::now()]]);
        // if($expired_transaction->count() > 0) {
        //     $expired_transaction->update(['status' => 'expired']);
        // }

        $transaction = Transaction::query();
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

    public function show(Transaction $transaction)
    {
        return ResponseFormatter::success(new TransactionDetailResource($transaction));
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'type' => ['required', 'in:merketplace,store'],
            'marketplace_resi' => [
                Rule::requiredIf($request->type == 'marketplace'),
                'file'
            ],
            'payment_method' => ['required', 'in:cod,transfer'],
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
                    return $query->where('status', 'active');
                })
            ],
            'transaction_product.*.image' => ['required', 'url'],
            'transaction_product.*.product_name' => ['required', 'string'],
            'transaction_product.*.discount' => ['required', 'integer'],
            'transaction_product.*.price' => ['required', 'integer'],
            'transaction_product.*.description' => ['required', 'string'],
            'transaction_product.*.quantity' => ['required', 'integer'],
            'transaction_product.*.notes' => ['required', 'string'],
        ]);
        $result = DB::transaction(function () use ($request) {
            $input = $request->except(['marketplace_resi', 'total_price']);
            $input['invoice_number'] = Transaction::max('invoice_number') + 1;
            if($request->type == 'marketplace') {
                $input['path'] = FileHelpers::upload_file('resi', $request->marketplace_resi);
            }
            $input['unique_code'] = rand(0,env("MAX_UNIQUE_CODE"));
            $input['total_price'] = $request->total_price + $input['unique_code'];
            $date = Carbon::now();
            $input['expired_time'] = ($request->payment_method == 'cod') ? null : $date->modify("+24 hours");
            $input['status'] = 'pending';
            $transaction = Transaction::create($input);

            $transaction->transaction_product()->createMany($request->transaction_product);
            foreach ($request->transaction_product as $transaction_product) {
                $product_combination = ProductCombination::where('product_slug', $transaction_product['product_slug'])->first();
                $product_combination->update([
                    'stock' => $product_combination->stock - $transaction_product['quantity']
                ]);
                $product_slugs[] = $transaction_product['product_slug'];
            }
            Cart::where('user_id', $request->user_id)->whereIn('product_slug', $product_slugs)->delete();
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
        $data_string = json_encode($data);
        $signature = hash_hmac('sha256', $data_string, $secret);        
        Log::info($data);
        Log::info($moota_signature);
        if($signature == $moota_signature) {
            if($data) {
                foreach ($data as $res) {
                    $transaction = Transaction::where([['status', 'pending'], ['total_price', $res['amount'], ['expired_time', '>=' , $res['date']]]])->first();
                    if(!empty($transaction)) {
                        $transaction->update([
                            'status' => 'paid_off',
                            'paid_off_time' => Carbon::now()
                        ]);
                        Log::notice("success update status transaction data");
                    } else {
                        Log::error("failed update status transaction data");
                    }
                }
            }
        } else {
            Log::error('signature is invalid');
        }
    }
}
