<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactions extends Migration
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
            $table->unsignedBigInteger('sub_distributor_id')->nullable();
            $table->string('invoice_number')->nullable();
            $table->bigInteger('quantity')->nullable();
            $table->bigInteger('sub_total')->nullable();
            $table->bigInteger('grand_total')->nullable();
            $table->string('status')->nullable();
            $table->text('refusal_reason')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('sub_distributor_id')->on('sub_distributors')->references('id')->onUpdate('cascade')->onDelete('cascade');
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
