<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class BrandTableSeeder extends Seeder
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
            DB::table('brands')->insert([
                'name' => $faker->text(20),
                'code' => $faker->numberBetween(1111,9999),
                'image' => $faker->imageUrl(),
                'rank' => $faker->numberBetween(1,100),
                'sort_order' => $faker->numberBetween(1,100),
                'status' => $faker->boolean(),
                'created_by' => $faker->numberBetween(1,20),
                'updated_by' => $faker->numberBetween(1,20),
                'created_at' => $faker->dateTime,
                'updated_at' => $faker->dateTime,
                'ip_address' => $faker->ipv4,
            ]);
        }
    }
}
