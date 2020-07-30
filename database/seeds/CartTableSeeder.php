<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CartTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('carts')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        DB::table('carts')->insert(
            [
                [
                    'id'                    => 1,
                    'sub_distributor_id'    => 1,
                    'product_id'            => 2,
                    'quantity'              => 4,
                    'note'                  => '',
                    'created_at'            => Carbon::now(),
                    'updated_at'            => Carbon::now(),
                ],
                [
                    'id'                    => 2,
                    'sub_distributor_id'    => 1,
                    'product_id'            => 3,
                    'quantity'              => 2,
                    'note'                  => '',
                    'created_at'            => Carbon::now(),
                    'updated_at'            => Carbon::now(),
                ],
            ]
        );
    }
}
