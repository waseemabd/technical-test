<?php

namespace Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CartProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Model::unguard();

        DB::table('cart_product')->insert([
            [

                'cart_id' => 1,
                'qty' => 2,
                'product_id' =>'1',

            ],
            [
                'cart_id' => 1,
                'qty' => 1,
                'product_id' =>'2',

            ],
            [
                'cart_id' => 1,
                'qty' => 1,
                'product_id' =>'3',

            ],

        ]);
    }
}
