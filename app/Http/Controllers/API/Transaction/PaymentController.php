<?php

namespace App\Http\Controllers\API\Transaction;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Resources\Transaction\PaymentResource;
use App\Models\Payment;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function update_status (Request $request, Payment $payment)
    {
        // form validation
        $request->validate([
            'status' => ['required', 'in:pending,process,paid_off,expired,canceled']
        ]);

        // update payment
        if($request->status == 'paid_off') {
            $inputPayment = [
                'status' => $request->status,
                'paid_off_time' => Carbon::now(),
            ];
        } else {
            $inputPayment = ['status' => $request->status];
        }
        $payment->update($inputPayment);
        // end udpdate payment

        // check payemnt if all paid off
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
        // end check payemnt if all paid off
        
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
}
