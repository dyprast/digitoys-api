<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RegionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('regions')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        DB::table('regions')->insert(
            [
                [
                    'id'            => 1,
                    'name'          => 'Aceh',
                    'created_at'    => Carbon::now(),
                    'updated_at'    => Carbon::now(),
                ],
                [
                    'id'            => 2,
                    'name'          => 'Medan',
                    'created_at'    => Carbon::now(),
                    'updated_at'    => Carbon::now(),
                ],
                [
                    'id'            => 3,
                    'name'          => 'Padang',
                    'created_at'    => Carbon::now(),
                    'updated_at'    => Carbon::now(),
                ],
                [
                    'id'            => 5,
                    'name'          => 'Lampung',
                    'created_at'    => Carbon::now(),
                    'updated_at'    => Carbon::now(),
                ],
                [
                    'id'            => 4,
                    'name'          => 'Jakarta',
                    'created_at'    => Carbon::now(),
                    'updated_at'    => Carbon::now(),
                ],
                [
                    'id'            => 6,
                    'name'          => 'Bandung',
                    'created_at'    => Carbon::now(),
                    'updated_at'    => Carbon::now(),
                ],
                [
                    'id'            => 7,
                    'name'          => 'Yogyakarta',
                    'created_at'    => Carbon::now(),
                    'updated_at'    => Carbon::now(),
                ],
                [
                    'id'            => 8,
                    'name'          => 'Surabaya',
                    'created_at'    => Carbon::now(),
                    'updated_at'    => Carbon::now(),
                ],
                [
                    'id'            => 9,
                    'name'          => 'Malang',
                    'created_at'    => Carbon::now(),
                    'updated_at'    => Carbon::now(),
                ],
                [
                    'id'            => 10,
                    'name'          => 'Bali',
                    'created_at'    => Carbon::now(),
                    'updated_at'    => Carbon::now(),
                ],
                [
                    'id'            => 11,
                    'name'          => 'Pontianak',
                    'created_at'    => Carbon::now(),
                    'updated_at'    => Carbon::now(),
                ],
                [
                    'id'            => 12,
                    'name'          => 'Banjarmasin',
                    'created_at'    => Carbon::now(),
                    'updated_at'    => Carbon::now(),
                ],
                [
                    'id'            => 13,
                    'name'          => 'Makassar',
                    'created_at'    => Carbon::now(),
                    'updated_at'    => Carbon::now(),
                ],
                [
                    'id'            => 14,
                    'name'          => 'Gorontalo',
                    'created_at'    => Carbon::now(),
                    'updated_at'    => Carbon::now(),
                ],
                [
                    'id'            => 15,
                    'name'          => 'Ambon',
                    'created_at'    => Carbon::now(),
                    'updated_at'    => Carbon::now(),
                ],
                [
                    'id'            => 16,
                    'name'          => 'Kupang',
                    'created_at'    => Carbon::now(),
                    'updated_at'    => Carbon::now(),
                ],
                [
                    'id'            => 17,
                    'name'          => 'Jayapura',
                    'created_at'    => Carbon::now(),
                    'updated_at'    => Carbon::now(),
                ],
                [
                    'id'            => 18,
                    'name'          => 'Wamena',
                    'created_at'    => Carbon::now(),
                    'updated_at'    => Carbon::now(),
                ]
            ]
        );
    }
}
