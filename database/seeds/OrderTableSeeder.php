<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OrderTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('orders')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        DB::table('orders')->insert(
            [
                [
                    'id'                    => 1,
                    'transaction_id'        => 1,
                    'sub_distributor_id'    => 1,
                    'product_id'            => 2,
                    'order_number'          => '#000001',
                    'quantity'              => 4,
                    'captured_price'        => 20000,
                    'sub_total'             => 20000,
                    'grand_total'           => 200000,
                    'status'                => '',
                    'refusal_reason'        => '',
                    'created_at'            => Carbon::now(),
                    'updated_at'            => Carbon::now(),
                ],
                [
                    'id'                    => 2,
                    'transaction_id'        => 2,
                    'sub_distributor_id'    => 1,
                    'product_id'            => 2,
                    'order_number'          => '#000002',
                    'quantity'              => 3,
                    'captured_price'        => 30000,
                    'sub_total'             => 30000,
                    'grand_total'           => 600000,
                    'status'                => '',
                    'refusal_reason'        => '',
                    'created_at'            => Carbon::now(),
                    'updated_at'            => Carbon::now(),
                ],
            ]
        );
    }
}
