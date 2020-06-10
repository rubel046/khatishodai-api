<?php

use App\Model\City;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class ZoneTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        DB::table("areas")->truncate();
        foreach (range(1, 500) as $index) {
            DB::table('areas')->insert([
                'city_id' => City::all()->random()->id,
                'name' => $faker->city,
                'zip_code' => $faker->postcode,
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
