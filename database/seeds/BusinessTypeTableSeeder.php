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
        foreach (range(1, 100) as $index) {
            DB::table('business_types')->insert([
                'name' => $faker->text(20),
                'status' => $faker->randomElement([1,2,3]),
                'created_by' => $faker->randomElement([1,2,3]),
                'updated_by' => $faker->randomElement([1,2,3]),
                'created_at' => $faker->dateTime,
                'updated_at' => $faker->dateTime,
                'ip_address' => $faker->ipv4,
            ]);
        }
    }
}
