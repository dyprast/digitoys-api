<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProductLabelTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('product_labels')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        DB::table('product_labels')->insert(
            [
                [
                    'name'          => 'Hot Release',
                    'color'         => '#e74c3c',
                    'created_at'    => Carbon::now(),
                    'updated_at'    => Carbon::now(),
                ],
                [
                    'name'          => 'New Release',
                    'color'          => '#f39c12',
                    'created_at'    => Carbon::now(),
                    'updated_at'    => Carbon::now(),
                ],
            ]
        );
    }
}
