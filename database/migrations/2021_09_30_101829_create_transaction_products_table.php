<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained('transactions')->onUpdate('cascade')->onDelete('cascade');
            $table->string('product_slug');
            $table->string('image');
            $table->string('product_name');
            $table->integer('price');
            $table->string('description');
            $table->integer('quantity');
        });

        Schema::table('transaction_products', function (Blueprint $table) {
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
        Schema::dropIfExists('transaction_products');
    }
}
