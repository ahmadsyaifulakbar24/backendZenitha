<?php

namespace App\Console\Commands;

use App\Models\Payment;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ExpiredCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'expired:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        info('----- Expired Check -----');

        $payment = Payment::where([
            ['expired_time', '<=', Carbon::now()],
            ['status', 'process']
        ]);
        $payment->update([ 'status' => 'expired']);
        $transaction_ids = $payment->pluck('transaction_id')->toArray();
        
        // update expired transaction
        $transaction = Transaction::whereIn('id', $transaction_ids);
        $transaction->update([ 'status' => 'expired' ]);
        return 0;
    }
}
