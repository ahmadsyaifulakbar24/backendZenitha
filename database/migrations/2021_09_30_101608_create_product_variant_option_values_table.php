<?php

use Facade\Ignition\Tabs\Tab;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductVariantOptionValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_variant_option_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_variant_option_id')->constrained('product_variant_options')->onDelete('cascade')->onUpdate('cascade');
            $table->string('variant_option_name');
        });

        Schema::table('product_images', function (Blueprint $table) {
            $table->foreign('product_variant_option_value_id')->references('id')->on('product_variant_option_values')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_variant_option_values');
    }
}
