<?php

namespace Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
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

        DB::table('users')->insert([
            [

                'id' => 1,
                'name' =>'admin',
                'email' =>'admin@gmail.com',
                'password' => bcrypt('123'),
                'role' =>'2',

            ],
            [
                'id' => 2,
                'name' =>'seller',
                'email' =>'seller@gmail.com',
                'password' => bcrypt('123'),
                'role' =>'1',

            ],
            [
                'id' => 3,
                'name' =>'customer',
                'email' =>'customer@gmail.com',
                'password' => bcrypt('123'),
                'role' =>'0',

            ],

        ]);
    }
}
