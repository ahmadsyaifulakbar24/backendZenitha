<?php

namespace App\Http\Controllers\API\Transaction;

use App\Helpers\FileHelpers;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Resources\Transaction\TransactionDetailResource;
use App\Http\Resources\Transaction\TransactionResource;
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
            'from_date' => ['nullable', 'date'],
            'till_date' => ['nullable', 'date'],
            'limit_page' => ['required', 'in:0,1'],
            'limit' => ['nullable', 'integer']
        ]);
        $limit = $request->input('limit', 10);
        $transaction = Transaction::query();
        if($request->user_id) {
            $transaction->where('user_id', $request->user_id);
        }

        if($request->from_date) 
        {
            $transaction->where('created_date', '>=', $request->from_date);
        }

        if($request->till_date) 
        {
            $transaction->where('created_date', '>=', $request->till_date);
        }
        $result = ($request->limit_page == 1) ? $transaction->paginate($limit) : $transaction->get();
        return ResponseFormatter::success(TransactionResource::collection($result));
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
            'shipping_cost' => ['required', 'integer'],
            'shipping_discount' => ['required', 'integer'],
            'total_price' => ['required', 'integer'],
            'address' => ['required', 'string'],
            'expedition' => ['required', 'string'],
            'payment_method' => ['required', 'in:cod,transfer'],
            'transaction_product' => ['required', 'array'],
            'transaction_product.*.product_slug' => ['required', 'string'],
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
            $input['unique_code'] = rand(0,999);
            $input['total_price'] = $request->total_price + $input['unique_code'];
            $date = Carbon::now();
            $input['expired_time'] = $date->modify("+24 hours");
            $input['status'] = 'pending';
            $transaction = Transaction::create($input);

            $transaction->transaction_product()->createMany($request->transaction_product);
            return $transaction;
        });

        return ResponseFormatter::success(new TransactionDetailResource($result), 'success create transaction data');
    }

    public function handle_moota(Request $request) {
        $secret = '2xQnWTwR';
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
                            'status' => 'paid_off'
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
