<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->string('invoice_number');
            $table->float('shipping_cost');
            $table->string('number_resi');
            $table->string('expedition');
            $table->string('marketplace_resi');
            $table->string('address');
            $table->float('shipping_discount');
            $table->float('product_discount');
            $table->string('total_price');
            $table->string('status');
            $table->string('payment_url');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('carts', function (Blueprint $table) {
            $table->foreign('product_slug')->references('product_slug')->on('product_combinations')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
