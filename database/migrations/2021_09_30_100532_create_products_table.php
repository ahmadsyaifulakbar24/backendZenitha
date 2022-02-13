<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onUpdate('cascade')->onUpdate('cascade');
            $table->string('product_slug');
            $table->string('sku')->nullable();
            $table->string('product_name');
            $table->foreignId('category_id')->constrained('categories')->onUpdate('cascade')->onUpdate('cascade');
            $table->foreignId('sub_category_id')->constrained('sub_categories')->onUpdate('cascade')->onDelete('cascade');
            $table->float('price');
            $table->integer('minimum_order');
            $table->boolean('preorder')->default(0);
            $table->enum('duration_unit', ['day', 'week'])->nullable();
            $table->integer('duration')->nullable();
            $table->text('description')->nullable();
            $table->string('video_url')->nullable();
            $table->integer('total_stock');
            $table->integer('product_weight');
            $table->enum('weight_unit', ['gram', 'kg']);
            $table->float('rate');
            $table->string('size_guide')->nullable();
            $table->enum('status', ['active', 'not_active']);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
