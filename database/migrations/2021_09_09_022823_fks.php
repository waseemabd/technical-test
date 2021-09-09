<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Fks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('products', function (Blueprint $table) {
            // ******************
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

        });

        Schema::table('carts', function (Blueprint $table) {
            // ******************
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

        });

        Schema::table('cart_product', function (Blueprint $table) {
            // ******************
            $table->foreignId('cart_id')->constrained('carts')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
