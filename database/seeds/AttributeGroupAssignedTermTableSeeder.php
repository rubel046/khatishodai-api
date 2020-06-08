<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class AttributeGroupAssignedTermTableSeeder extends Seeder
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
            DB::table('attribute_group_assigned_terms')->insert([
                'attribute_group_id' => $faker->numberBetween(1,100),
                'attribute_id' => $faker->numberBetween(1,100),
                'attribute_term_ids' => $faker->randomElement([ '1,2,3,4,9', '4,5,6,4,29', '5,7,3,24,9', '2,2,13,7,6', ]),
                'is_variation_maker' => $faker->boolean(),
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
