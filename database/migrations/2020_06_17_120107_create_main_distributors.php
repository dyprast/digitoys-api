<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMainDistributors extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('main_distributors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sub_region_id')->nullable();
            $table->string('name')->nullable();
            $table->string('username')->nullable();
            $table->string('password')->nullable();
            $table->string('email')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('remember_token')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('sub_region_id')->on('sub_regions')->references('id')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('main_distributors');
    }
}
