<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class MainDistributorTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('main_distributors')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        DB::table('main_distributors')->insert(
            [
                [
                    'sub_region_id' => 1,
                    'name'          => 'John Doe',
                    'username'      => 'maindist',
                    'password'      => Hash::make('maindist'),
                    'email'         => 'maindist@digitoys.com',
                    'phone_number'  => '62838123123',
                    'created_at'    => Carbon::now(),
                    'updated_at'    => Carbon::now(),
                ],
                [
                    'sub_region_id' => 2,
                    'name'          => 'Jean Haversine',
                    'username'      => 'maindist2',
                    'password'      => Hash::make('maindist2'),
                    'email'         => 'maindist2@digitoys.com',
                    'phone_number'  => '62838123123',
                    'created_at'    => Carbon::now(),
                    'updated_at'    => Carbon::now(),
                ],
                [
                    'sub_region_id' => 3,
                    'name'          => 'Frederick Hansel',
                    'username'      => 'maindist3',
                    'password'      => Hash::make('maindist3'),
                    'email'         => 'maindist3@digitoys.com',
                    'phone_number'  => '62838123123',
                    'created_at'    => Carbon::now(),
                    'updated_at'    => Carbon::now(),
                ],
                [
                    'sub_region_id' => 4,
                    'name'          => 'Rein Raffles',
                    'username'      => 'maindist4',
                    'password'      => Hash::make('maindist4'),
                    'email'         => 'maindist4@digitoys.com',
                    'phone_number'  => '62838123123',
                    'created_at'    => Carbon::now(),
                    'updated_at'    => Carbon::now(),
                ],
                [
                    'sub_region_id' => 5,
                    'name'          => 'Selva Aditi',
                    'username'      => 'maindist5',
                    'password'      => Hash::make('maindist5'),
                    'email'         => 'maindist5@digitoys.com',
                    'phone_number'  => '62838123123',
                    'created_at'    => Carbon::now(),
                    'updated_at'    => Carbon::now(),
                ],
                [
                    'sub_region_id' => 6,
                    'name'          => 'Hanselion Deth',
                    'username'      => 'maindist6',
                    'password'      => Hash::make('maindist6'),
                    'email'         => 'maindist6@digitoys.com',
                    'phone_number'  => '62838123123',
                    'created_at'    => Carbon::now(),
                    'updated_at'    => Carbon::now(),
                ],
            ]
        );
    }
}
