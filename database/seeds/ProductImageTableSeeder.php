<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProductImageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('product_images')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        DB::table('product_images')->insert(
            [
                [
                    'id'                    => 1,
                    'product_id'            => 1,
                    'path'                  => '/img/product_image/dummy_1.jpg',
                    'created_at'            => Carbon::now(),
                    'updated_at'            => Carbon::now(),
                ],
                [
                    'id'                    => 2,
                    'product_id'            => 2,
                    'path'                  => '/img/product_image/dummy_2.jpg',
                    'created_at'            => Carbon::now(),
                    'updated_at'            => Carbon::now(),
                ],
                [
                    'id'                    => 3,
                    'product_id'            => 3,
                    'path'                  => '/img/product_image/dummy_3.jpg',
                    'created_at'            => Carbon::now(),
                    'updated_at'            => Carbon::now(),
                ],
                [
                    'id'                    => 4,
                    'product_id'            => 4,
                    'path'                  => '/img/product_image/dummy_4.jpg',
                    'created_at'            => Carbon::now(),
                    'updated_at'            => Carbon::now(),
                ],
                [
                    'id'                    => 5,
                    'product_id'            => 5,
                    'path'                  => '/img/product_image/dummy_5.jpg',
                    'created_at'            => Carbon::now(),
                    'updated_at'            => Carbon::now(),
                ],
                [
                    'id'                    => 6,
                    'product_id'            => 6,
                    'path'                  => '/img/product_image/dummy_6.jpg',
                    'created_at'            => Carbon::now(),
                    'updated_at'            => Carbon::now(),
                ],
                [
                    'id'                    => 7,
                    'product_id'            => 7,
                    'path'                  => '/img/product_image/dummy_7.jpg',
                    'created_at'            => Carbon::now(),
                    'updated_at'            => Carbon::now(),
                ],
                [
                    'id'                    => 8,
                    'product_id'            => 8,
                    'path'                  => '/img/product_image/dummy_8.jpg',
                    'created_at'            => Carbon::now(),
                    'updated_at'            => Carbon::now(),
                ],
                [
                    'id'                    => 9,
                    'product_id'            => 9,
                    'path'                  => '/img/product_image/dummy_9.jpg',
                    'created_at'            => Carbon::now(),
                    'updated_at'            => Carbon::now(),
                ],
                [
                    'id'                    => 10,
                    'product_id'            => 10,
                    'path'                  => '/img/product_image/dummy_10.jpg',
                    'created_at'            => Carbon::now(),
                    'updated_at'            => Carbon::now(),
                ],
            ]
        );
    }
}
