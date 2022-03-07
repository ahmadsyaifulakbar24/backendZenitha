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
            $table->string('invoice_number')->unique();
            $table->string('number_resi')->nullable();
            $table->string('marketplace_resi')->nullable();
            $table->enum('type', ['marketplace', 'store']);

            $table->bigInteger('shipping_cost');
            $table->bigInteger('shipping_discount');
            $table->string('total_price');
            $table->string('unique_code');

            $table->string('address');
            $table->string('expedition');
            $table->dateTime('expired_time');
            $table->enum('payment_method', ['cod', 'transfer']);
            $table->string('status');
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
