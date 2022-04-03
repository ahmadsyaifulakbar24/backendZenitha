<?php

namespace App\Http\Controllers\API\Report;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
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

        $transaction = Transaction::query();
        if($request->type == 'year') {
            $transaction->select(
                DB::raw("MONTHNAME(paid_off_time) as name"),
                DB::raw("SUM(total_price) as total")
            )->where([
                [DB::raw("DATE_FORMAT(paid_off_time, '%Y-%m-%d')"), '>=', $request->from_date],
                [DB::raw("DATE_FORMAT(paid_off_time, '%Y-%m-%d')"), '<=', $request->until_date],
            ])
            ->whereIn('status', ['paid_off', 'sent', 'finish'])
            ->groupBYRaw("YEAR(paid_off_time), MONTH(paid_off_time)");
        } else {
            $transaction->select(
                DB::raw("DAYNAME(paid_off_time) as name"),
                DB::raw("SUM(total_price) as total")
            )->where([
                [DB::raw("DATE_FORMAT(paid_off_time, '%Y-%m-%d')"), '>=', $request->from_date],
                [DB::raw("DATE_FORMAT(paid_off_time, '%Y-%m-%d')"), '<=', $request->until_date],
            ])
            ->whereIn('status', ['paid_off', 'sent', 'finish'])
            ->groupBYRaw("YEAR(paid_off_time), MONTH(paid_off_time), DAY(paid_off_time)");
        }
        return ResponseFormatter::success($transaction->get(), 'success get turnover data');
    }
}
