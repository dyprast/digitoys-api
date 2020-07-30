<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaction_id')->nullable();
            $table->unsignedBigInteger('main_distributor_id')->nullable();
            $table->unsignedBigInteger('sub_distributor_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->string('order_number')->nullable();
            $table->bigInteger('quantity')->nullable();
            $table->bigInteger('captured_price')->nullable();
            $table->bigInteger('sub_total')->nullable();
            $table->bigInteger('grand_total')->nullable();
            $table->string('status')->nullable();
            $table->text('refusal_reason')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('transaction_id')->on('transactions')->references('id')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('main_distributor_id')->on('main_distributors')->references('id')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('orders');
    }
}
