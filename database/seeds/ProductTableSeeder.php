<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */ 
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('products')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        DB::table('products')->insert(
            [
                [
                    'id'                    => 1,
                    'category_id'           => 1,
                    'product_label_id'      => 1,
                    'name'                  => 'Sample Product 1',
                    'price'                 => '300000',
                    'stock'                 => '49',
                    'created_at'            => Carbon::now(),
                    'updated_at'            => Carbon::now(),
                ],
                [
                    'id'                    => 2,
                    'category_id'           => 1,
                    'product_label_id'      => 2,
                    'name'                  => 'Sample Product 2',
                    'price'                 => '10000',
                    'stock'                 => '99',
                    'created_at'            => Carbon::now(),
                    'updated_at'            => Carbon::now(),
                ],
                [
                    'id'                    => 3,
                    'category_id'           => 2,
                    'product_label_id'      => 2,
                    'name'                  => 'Sample Product 3',
                    'price'                 => '1000000',
                    'stock'                 => '99',
                    'created_at'            => Carbon::now(),
                    'updated_at'            => Carbon::now(),
                ],
                [
                    'id'                    => 4,
                    'category_id'           => 1,
                    'product_label_id'      => 2,
                    'name'                  => 'Sample Product 4',
                    'price'                 => '10000',
                    'stock'                 => '82',
                    'created_at'            => Carbon::now(),
                    'updated_at'            => Carbon::now(),
                ],
                [
                    'id'                    => 5,
                    'category_id'           => 1,
                    'product_label_id'      => 2,
                    'name'                  => 'Sample Product 5',
                    'price'                 => '100000',
                    'stock'                 => '88',
                    'created_at'            => Carbon::now(),
                    'updated_at'            => Carbon::now(),
                ],
                [
                    'id'                    => 6,
                    'category_id'           => 1,
                    'product_label_id'      => 2,
                    'name'                  => 'Sample Product 6',
                    'price'                 => '20000',
                    'stock'                 => '99',
                    'created_at'            => Carbon::now(),
                    'updated_at'            => Carbon::now(),
                ],
                [
                    'id'                    => 7,
                    'category_id'           => 1,
                    'product_label_id'      => 2,
                    'name'                  => 'Sample Product 7',
                    'price'                 => '40000',
                    'stock'                 => '99',
                    'created_at'            => Carbon::now(),
                    'updated_at'            => Carbon::now(),
                ],
                [
                    'id'                    => 8,
                    'category_id'           => 1,
                    'product_label_id'      => 2,
                    'name'                  => 'Sample Product 8',
                    'price'                 => '90000',
                    'stock'                 => '99',
                    'created_at'            => Carbon::now(),
                    'updated_at'            => Carbon::now(),
                ],
                [
                    'id'                    => 9,
                    'category_id'           => 1,
                    'product_label_id'      => 2,
                    'name'                  => 'Sample Product 9',
                    'price'                 => '10000',
                    'stock'                 => '99',
                    'created_at'            => Carbon::now(),
                    'updated_at'            => Carbon::now(),
                ],
                [
                    'id'                    => 10,
                    'category_id'           => 1,
                    'product_label_id'      => 2,
                    'name'                  => 'Sample Product 10',
                    'price'                 => '110000',
                    'stock'                 => '99',
                    'created_at'            => Carbon::now(),
                    'updated_at'            => Carbon::now(),
                ],
            ]
        );
    }
}
