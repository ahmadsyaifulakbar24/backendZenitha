<?php

namespace App\Http\Controllers\API\Report;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Resources\Transaction\SalesReportResource;
use App\Models\Payment;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function activity_transaction(Request $request)
    {
        $request->validate([
            'from_date' => ['required', 'date_format:Y-m-d'],
            'until_date' => ['required', 'date_format:Y-m-d', 'after_or_equal:from_date'],
        ]);
        $activity_transaction = Transaction::activityTransaction()
                                ->where([
                                    [DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"), '>=', $request->from_date],
                                    [DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"), '<=', $request->until_date],
                                ])->get();
        return ResponseFormatter::success($activity_transaction, 'success get activity transaction data');
    }

    public function turnover(Request $request)
    {
        $request->validate([
            'type' => ['required', 'in:month,year'],
            'from_date' => ['required', 'date_format:Y-m-d'],
            'until_date' => ['required', 'date_format:Y-m-d', 'after_or_equal:from_date'],
        ]);

        $payment = Payment::query();
        if($request->type == 'year') {
            $payment->select(
                DB::raw("MONTHNAME(paid_off_time) as name"),
                DB::raw("SUM(total) as total")
            )->where([
                [DB::raw("DATE_FORMAT(paid_off_time, '%Y-%m-%d')"), '>=', $request->from_date],
                [DB::raw("DATE_FORMAT(paid_off_time, '%Y-%m-%d')"), '<=', $request->until_date],
            ])
            ->where('status', 'paid_off')
            ->groupBYRaw("YEAR(paid_off_time), MONTH(paid_off_time)");
        } else {
            $payment->select(
                DB::raw("DAYNAME(paid_off_time) as name"),
                DB::raw("SUM(total) as total")
            )->where([
                [DB::raw("DATE_FORMAT(paid_off_time, '%Y-%m-%d')"), '>=', $request->from_date],
                [DB::raw("DATE_FORMAT(paid_off_time, '%Y-%m-%d')"), '<=', $request->until_date],
            ])
            ->where('status', 'paid_off')
            ->groupBYRaw("YEAR(paid_off_time), MONTH(paid_off_time), DAY(paid_off_time)");
        }
        return ResponseFormatter::success($payment->get(), 'success get turnover data');
    }

    public function sales(Request $request)
    {
        $request->validate([
            'user_id' => ['nullable', 'exists:users,id'],
            'from_date' => ['required', 'date_format:Y-m-d'],
            'till_date' => ['required', 'date_format:Y-m-d'],
            'limit_page' => ['required', 'in:0,1'],
            'limit' => ['nullable', 'integer'],
        ]);
        $limit = $request->input('limit', 10);

        $transaction = Transaction::query();
        $transaction->where(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"), '>=', $request->from_date)
                    ->where(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"), '<=', $request->till_date);
        if($request->user_id) {
            $transaction->where('user_id', $request->user_id);
        } else {
            $transaction->groupBy('user_id');
        }
        $users = $transaction->pluck('user_id')->toArray();

        $user = User::with(['transaction' => function($query) use ($request){
            $query->where(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"), '>=', $request->from_date)
            ->where(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"), '<=', $request->till_date);
        }])->whereIn('id', $users);

        if($request->limit_page) {
           $result = $user->paginate($limit);
        } else {
            $result = $user->get();
        }
        return ResponseFormatter::success(SalesReportResource::collection($result)->response()->getData(true), 'success get sales report data');
    }
}