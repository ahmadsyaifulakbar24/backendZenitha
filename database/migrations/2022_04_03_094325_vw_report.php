<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class VwReport extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("DROP VIEW IF EXISTS vw_activity_transaction");
        DB::statement("
            CREATE VIEW vw_activity_transaction as
            SELECT 
                COUNT(if(status = 'pending', status, null)) as pending,
                COUNT(if(status = 'paid_off', status, null)) as paid_off,
                COUNT(if(status = 'expired', status, null)) as expired,
                COUNT(if(status = 'sent', status, null)) as sent,
                COUNT(if(status = 'canceled', status, null)) as canceled
                COUNT(*)) as total
            FROM transactions;
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
