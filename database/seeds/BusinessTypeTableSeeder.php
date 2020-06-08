<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class BusinessTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $typeArray=['Wholesaler','Retailer','Manufacturer','Supplier','Exporter','Distributor','Importer','Service Provider','Buying House','Trader','Wholesaler','Retailer'];
        foreach ($typeArray as $val) {
            DB::table('business_types')->insert([
                'name' => $val,
                'status' => $faker->boolean(),
                'created_by' => $faker->randomElement([1,2,3]),
                'updated_by' => $faker->randomElement([1,2,3]),
                'created_at' => $faker->dateTime,
                'updated_at' => $faker->dateTime,
                'ip_address' => $faker->ipv4,
            ]);
        }
    }
}
