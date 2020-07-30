<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('admins')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        DB::table('admins')->insert(
            [
                [
                    'name'          => 'admin',
                    'username'      => 'admin',
                    'password'      => Hash::make('admin'),
                    'email'         => 'admin@digitoys.com',
                    'phone_number'  => '62838123123',
                    'created_at'    => Carbon::now(),
                    'updated_at'    => Carbon::now(),
                ]
            ]
        );
    }
}
