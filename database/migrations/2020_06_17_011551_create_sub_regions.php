<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubRegions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_regions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('region_id')->nullable();
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('region_id')->on('regions')->references('id')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sub_regions');
    }
}
