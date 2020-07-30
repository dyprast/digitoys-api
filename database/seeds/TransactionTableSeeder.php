<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TransactionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('transactions')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        DB::table('transactions')->insert(
            [
                [
                    'id'                    => 1,
                    'sub_distributor_id'    => 1,
                    'invoice_number'        => 'INV20200729000001',
                    'quantity'              => 4,
                    'sub_total'             => 200000,
                    'grand_total'           => 200000,
                    'status'                => '',
                    'refusal_reason'        => '',
                    'created_at'            => Carbon::now(),
                    'updated_at'            => Carbon::now(),
                ],
                [
                    'id'                    => 2,
                    'sub_distributor_id'    => 1,
                    'invoice_number'        => 'INV20200729000002',
                    'quantity'              => 3,
                    'sub_total'             => 100000,
                    'grand_total'           => 100000,
                    'status'                => '',
                    'refusal_reason'        => '',
                    'created_at'            => Carbon::now(),
                    'updated_at'            => Carbon::now(),
                ],
            ]
        );
    }
}
