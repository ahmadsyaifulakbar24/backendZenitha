<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('web_settings', function (Blueprint $table) {
            $table->id();
            $table->string('logo')->nullable();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('email');
            $table->string('phone');
            $table->foreignId('province_id')->constrained('provinces')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('city_id')->constrained('cities')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('district_id')->constrained('districts')->onDelete('cascade')->onUpdate('cascade');
            $table->integer('postal_code')->nullable();
            $table->string('address')->nullable();

            $table->boolean('fb_status')->comment('facebook status');
            $table->string('fb')->nullable()->comment('facebook status');
            $table->boolean('tw_status')->comment('twitter status');
            $table->string('tw')->nullable()->comment('twitter');
            $table->boolean('yt_status')->comment('youtube status');
            $table->string('yt')->nullable()->comment('youtube');
            $table->boolean('ig_status')->comment('instagram status');
            $table->string('ig')->nullable()->comment('instagram');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('web_settings');
    }
}
