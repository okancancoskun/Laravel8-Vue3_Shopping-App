<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartCartItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cart_cart_items', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('cart_items_id')->unsigned()->index();
            $table->bigInteger('cart_id')->unsigned()->index();
            $table->timestamps();

            $table->foreign('cart_items_id')->references('id')->on('cart_items')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cart_cart_items');
    }
}
