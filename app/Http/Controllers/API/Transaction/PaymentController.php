<?php

namespace App\Http\Controllers\API\Transaction;

use App\Helpers\FileHelpers;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Resources\Transaction\PaymentResource;
use App\Models\Payment;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function get(Request $request) {
        $request->validate([
            'user_id' => ['nullable', 'exists:users,id'],
            'status' => ['required', 'in:pending,process,paid_off,expired,canceled']
        ]);
        $payment =  Payment::where([
            ['status', $request->status],
            ['unique_code', '!=', null]
        ]);

        if($request->user_id) {
            $payment->where('user_id', $request->user_id);
        }
        return ResponseFormatter::success(PaymentResource::collection($payment->get()), 'success get payment data');
    }

    public function show(Payment $payment)
    {
        return ResponseFormatter::success(new PaymentResource($payment), 'success get payment detail data');
    }

    public function update_status (Request $request, Payment $payment)
    {
        // form validation
        $request->validate([
            'status' => ['required', 'in:pending,process,paid_off,expired,canceled']
        ]);

        DB::transaction(function () use ($request, $payment) {
            // update payment
            if($request->status == 'paid_off') {
                $inputPayment = [
                    'status' => $request->status,
                    'paid_off_time' => Carbon::now(),
                ];
            } else {
                $inputPayment = ['status' => $request->status];
            }
            $child = Payment::where('parent_id', $payment->id);
            $payment->update($inputPayment);
            $child->where('order_payment', 1)->update($inputPayment);
            // end udpdate payment
            
            // check payemnt if all paid off
            if($payment->order_payment == 0) {
                $transactioin_arr = $child->distinct()->pluck('transaction_id')->toArray();
            } else {
                $transactioin_arr = [$payment->transaction_id];
            }
    
            foreach($transactioin_arr as $transaction_id) {
                $cek_payment = Payment::where([
                    ['transaction_id', $transaction_id],
                    ['status', '!=', 'paid_off']
                ])->count();
    
                if($cek_payment == 0) {
                    Transaction::find($transaction_id)->update([
                        'status' => 'paid_off',
                        'paid_off_time' => Carbon::now()
                    ]);
                }
                // end check payemnt if all paid off
            }
        });
        
        return ResponseFormatter::success(new PaymentResource($payment), 'success update payment status data');
    }

    public function triger_payement_po(Payment $payment)
    {
        // if($payment->transaction->payment_method == 'po') {

        // }        
        if($payment->status == 'pending') {
            $unique_code = rand(0,env("MAX_UNIQUE_CODE"));
            $date = Carbon::now();
            $expired_time = $date->modify("+24 hours");
    
            $payment->update([
                'unique_code' => $unique_code,
                'expired_time' => $expired_time,
                'total' => $payment->total + $unique_code,
                'status' => 'process'
            ]);
    
            return ResponseFormatter::success(new PaymentResource($payment), 'success triger second payment po data');
        } else {
            return ResponseFormatter::error([
                'message' => 'error triger second payment po data'
            ], 'triger second payment failed', 422);
        }
    }

    public function upload_evidence(Request $request, Payment $payment)
    {
        
        $request->validate([
            'evidence' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg'],
        ]);

        if($payment->evidence()->count() < 1 ) {
            $input['evidence'] = FileHelpers::upload_file('evidence', $request->evidence);
            $payment->evidence()->create($input);
            return ResponseFormatter::success(new PaymentResource($payment));
        } else {
            return ResponseFormatter::errorValidation([
                'payment' => 'evidence is already exists'
            ], 'upload evidence failed');
        }
    }
}
