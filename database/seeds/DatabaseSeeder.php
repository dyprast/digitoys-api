<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call('UsersTableSeeder');
        $this->call('RegionTableSeeder');
        $this->call('SubRegionTableSeeder');
        $this->call('AdminTableSeeder');
        $this->call('MainDistributorTableSeeder');
        $this->call('SubDistributorTableSeeder');
        $this->call('NewsTableSeeder');
        $this->call('CategoryTableSeeder');
        $this->call('ProductLabelTableSeeder');
        $this->call('ProductTableSeeder');
        $this->call('ProductImageTableSeeder');
        $this->call('CartTableSeeder');
        $this->call('TransactionTableSeeder');
        $this->call('OrderTableSeeder');
    }
}
