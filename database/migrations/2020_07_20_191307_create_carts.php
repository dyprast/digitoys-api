<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sub_distributor_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->bigInteger('quantity')->nullable();
            $table->string('note')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('sub_distributor_id')->on('sub_distributors')->references('id')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('product_id')->on('products')->references('id')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('carts');
    }
}
