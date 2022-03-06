<?php

namespace App\Http\Controllers\API\Transaction;

use App\Helpers\FileHelpers;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Resources\Transaction\TransactionDetailResource;
use App\Models\Transaction;
use Carbon\Carbon;
use Facade\FlareClient\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class TransactionController extends Controller
{
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
            'product_discount' => ['required', 'integer'],
            'product_price' => ['required', 'integer'],
            'total_price' => ['required', 'integer'],
            'address' => ['required', 'string'],
            'expedition' => ['required', 'string'],
            'payment_method' => ['required', 'in:cod,transfer']
        ]);

        $input = $request->except(['marketplace_resi']);
        $input['invoice_number'] = Transaction::max('invoice_number') + 1;
        if($request->type == 'marketplace') {
            $input['path'] = FileHelpers::upload_file('resi', $request->marketplace_resi);
        }
        $input['unique_code'] = rand(0,999);
        $date = Carbon::now();
        $input['expired_time'] = $date->modify("+24 hours");
        $input['status'] = 'pending';
        $transaction = Transaction::create($input);

        return ResponseFormatter::success(new TransactionDetailResource($transaction), 'success create transaction data');
    }

    public function handle_moota(Request $request) {
        $secret = '2xQnWTwR';
        $moota_signature = $request->header('signature');
        $data = $request->json()->all();
        $data_string = json_encode($data);
        $signature = hash_hmac('sha256', $data_string, $secret);
        Log::info($data);
        Log::info($moota_signature);
        // $notification = file_get_contents("https://ahmadsyaifulakbar.com/moota_response.json");
        // $response = json_decode($notification, TRUE);
        // if($notification) {
        //     foreach ($response as $res) {
        //         $unique_code = substr($res['amount'], -3);
        //         $transaction = Transaction::where([['status', 'pending'], ['unique_code', $unique_code]])->first();
        //         if(!empty($transaction)) {
        //             $transaction->update([
        //                 'status' => 'paid_off'
        //             ]);
        //             return ResponseFormatter::success(null, 'success update status transaction data');
        //         } else {
        //             return ResponseFormatter::error(null, 'failed update status transaction data', 404);
        //         }
        //     }
        // }
    }
}
