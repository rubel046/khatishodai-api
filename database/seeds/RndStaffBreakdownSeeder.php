<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class RndStaffBreakdownSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        // DB::table("rnd_staff_breakdowns")->truncate();
        foreach (range(1, 100) as $index) {
            DB::table('rnd_staff_breakdowns')->insert([
                'name' => $faker->text(15),
                'status' => $faker->boolean,
                'created_by' => $faker->randomElement([1,2,3]),
                'updated_by' => $faker->randomElement([1,2,3]),
                'created_at' => $faker->dateTime,
                'updated_at' => $faker->dateTime,
                'ip_address' => $faker->ipv4,
            ]);
        }
    }
}
