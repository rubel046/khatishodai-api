<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class CategoryTableSeeder extends Seeder
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
            DB::table('categories')->insert([
                'parent_id' => $faker->numberBetween(1,20),
                'name' => $faker->text(20),
                'image' => $faker->imageUrl(),
                'description' => $faker->text(200),
                'rank' => $faker->numberBetween(1,100),
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
