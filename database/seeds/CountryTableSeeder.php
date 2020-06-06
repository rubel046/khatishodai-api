<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;


class CountryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $faker = Faker::create();
        foreach (range(1, 100) as $index) {
            DB::table('countries')->insert([
                'name' => $faker->country,
                'code' => $faker->countryCode,
                'code_a3' => $faker->countryCode,
                'code_n3' => $faker->countryCode,
                'lat' => $faker->postcode(10),
                'long' => $faker->postcode(15),
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
