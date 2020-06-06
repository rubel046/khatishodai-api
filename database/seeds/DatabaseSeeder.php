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
        $this->call('BusinessTypeTableSeeder');
        $this->call('CountryTableSeeder');
        $this->call('DivisionTableSeeder');
        $this->call('CityTableSeeder');
        $this->call('ZoneTableSeeder');
        $this->call('BrandTableSeeder');
        $this->call('CategoryTableSeeder');
        $this->call('AttributeTableSeeder');
        $this->call('AttributeTermsTableSeeder');
        $this->call('AttributeGroupTableSeeder');
        $this->call('AttributeGroupAssignedTermTableSeeder');
    }
}
