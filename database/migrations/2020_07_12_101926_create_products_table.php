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
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('product_label_id')->nullable();
            $table->string('product_code')->nullable();
            $table->string('name')->nullable();
            $table->string('label')->nullable();
            $table->bigInteger('price')->nullable();
            $table->bigInteger('stock')->nullable();
            $table->longText('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('category_id')->on('categories')->references('id')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('product_label_id')->on('product_labels')->references('id')->onUpdate('cascade')->onDelete('cascade');
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
