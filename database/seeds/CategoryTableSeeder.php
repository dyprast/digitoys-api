<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('categories')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        DB::table('categories')->insert(
            [
                [
                    'id'                    => 1,
                    'parent_category_id'    => null,
                    'name'                  => 'Elektronik',
                    'description'           => '',
                    'icon'                  => '',
                    'banner'                => '',
                    'created_at'            => Carbon::now(),
                    'updated_at'            => Carbon::now(),
                ],
                [
                    'id'                    => 2,
                    'parent_category_id'    => 1,
                    'name'                  => 'Smartphone',
                    'description'           => '',
                    'icon'                  => '',
                    'banner'                => '',
                    'created_at'            => Carbon::now(),
                    'updated_at'            => Carbon::now(),
                ],
                [
                    'id'                    => 3,
                    'parent_category_id'    => 1,
                    'name'                  => 'Laptop',
                    'description'           => '',
                    'icon'                  => '',
                    'banner'                => '',
                    'created_at'            => Carbon::now(),
                    'updated_at'            => Carbon::now(),
                ],
            ]
        );
    }
}
