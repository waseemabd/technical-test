<?php

namespace Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
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

        DB::table('products')->insert([
            [

                'id' => 1,
                'title' =>'laptop',
                'price' =>'400',
                'desc' => '',
                'image' =>'',
                'user_id' =>'2',

            ],
            [
                'id' => 2,
                'title' =>'mobile',
                'price' =>'200',
                'desc' => '',
                'image' =>'',
                'user_id' =>'2',

            ],
            [
                'id' => 3,
                'title' =>'tab',
                'price' =>'50',
                'desc' => '',
                'image' =>'',
                'user_id' =>'2',

            ],

        ]);
    }
}
