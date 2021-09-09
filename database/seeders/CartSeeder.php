<?php

namespace Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CartSeeder extends Seeder
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

        DB::table('carts')->insert([
            [

                'id' => 1,
                'user_id' =>'3',

            ],


        ]);
    }
}
