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
                                    [DB::raw("DATE_FORMAT(updated_at, '%Y-%m-%d')"), '>=', $request->from_date],
                                    [DB::raw("DATE_FORMAT(updated_at, '%Y-%m-%d')"), '<=', $request->until_date],
                                ])->get();
        return ResponseFormatter::success($activity_transaction, 'success get activity transaction data');
    }
}
