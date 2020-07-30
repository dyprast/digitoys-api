<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class SubDistributorTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('sub_distributors')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        DB::table('sub_distributors')->insert(
            [
                [
                    'sub_region_id' => 1,
                    'name'          => 'Doe Joanne',
                    'username'      => 'subdist',
                    'password'      => Hash::make('subdist'),
                    'email'         => 'subdist@digitoys.com',
                    'phone_number'  => '62838123123',
                    'status'        => 'active',
                    'created_at'    => Carbon::now(),
                    'updated_at'    => Carbon::now(),
                ],
                [
                    'sub_region_id' => 2,
                    'name'          => 'Haver Jeanne',
                    'username'      => 'subdist2',
                    'password'      => Hash::make('subdist2'),
                    'email'         => 'subdist2@digitoys.com',
                    'phone_number'  => '62838123123',
                    'status'        => 'active',
                    'created_at'    => Carbon::now(),
                    'updated_at'    => Carbon::now(),
                ],
                [
                    'sub_region_id' => 3,
                    'name'          => 'Hansela Frederick',
                    'username'      => 'subdist3',
                    'password'      => Hash::make('subdist3'),
                    'email'         => 'subdist3@digitoys.com',
                    'phone_number'  => '62838123123',
                    'status'        => 'active',
                    'created_at'    => Carbon::now(),
                    'updated_at'    => Carbon::now(),
                ],
                [
                    'sub_region_id' => 4,
                    'name'          => 'Rafflesia Reina',
                    'username'      => 'subdist4',
                    'password'      => Hash::make('subdist4'),
                    'email'         => 'subdist4@digitoys.com',
                    'phone_number'  => '62838123123',
                    'status'        => 'active',
                    'created_at'    => Carbon::now(),
                    'updated_at'    => Carbon::now(),
                ],
                [
                    'sub_region_id' => 5,
                    'name'          => 'Aditi Selva',
                    'username'      => 'subdist5',
                    'password'      => Hash::make('subdist5'),
                    'email'         => 'subdist5@digitoys.com',
                    'phone_number'  => '62838123123',
                    'status'        => 'active',
                    'created_at'    => Carbon::now(),
                    'updated_at'    => Carbon::now(),
                ],
                [
                    'sub_region_id' => 6,
                    'name'          => 'Deth Hanselion',
                    'username'      => 'subdist6',
                    'password'      => Hash::make('subdist6'),
                    'email'         => 'subdist6@digitoys.com',
                    'phone_number'  => '62838123123',
                    'status'        => 'active',
                    'created_at'    => Carbon::now(),
                    'updated_at'    => Carbon::now(),
                ],
            ]
        );
    }
}
