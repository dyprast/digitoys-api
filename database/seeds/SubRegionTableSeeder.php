<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SubRegionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('sub_regions')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        DB::table('sub_regions')->insert(
            [
                [
                    'id'            => 1,
                    'region_id'     => 1,
                    'name'          => 'Jakarta Utara',
                    'created_at'    => Carbon::now(),
                    'updated_at'    => Carbon::now(),
                ],
                [
                    'id'            => 2,
                    'region_id'     => 1,
                    'name'          => 'Jakarta Pusat',
                    'created_at'    => Carbon::now(),
                    'updated_at'    => Carbon::now(),
                ],
                [
                    'id'            => 3,
                    'region_id'     => 1,
                    'name'          => 'Jakarta Barat',
                    'created_at'    => Carbon::now(),
                    'updated_at'    => Carbon::now(),
                ],
                [
                    'id'            => 4,
                    'region_id'     => 1,
                    'name'          => 'Jakarta Timur',
                    'created_at'    => Carbon::now(),
                    'updated_at'    => Carbon::now(),
                ],
                [
                    'id'            => 5,
                    'region_id'     => 1,
                    'name'          => 'Jakarta Selatan',
                    'created_at'    => Carbon::now(),
                    'updated_at'    => Carbon::now(),
                ],
                [
                    'id'            => 6,
                    'region_id'     => 1,
                    'name'          => 'Kepulauan Seribu',
                    'created_at'    => Carbon::now(),
                    'updated_at'    => Carbon::now(),
                ],
            ]
        );
    }
}
