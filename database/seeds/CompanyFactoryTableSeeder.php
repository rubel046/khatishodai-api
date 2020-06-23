<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use App\Model\Company;

class CompanyFactoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        foreach (range(1, 50) as $index) {
            DB::table('company_factories')->insert([
                'company_id' => Company::all()->random()->id,
                'name' => $faker->text(20),
                'size_id' => $faker->numberBetween(1, 20),
                'staff_number_id' => $faker->numberBetween(1, 20),
                'rnd_staff_id' => $faker->numberBetween(1, 20),
                'production_line_id' => $faker->numberBetween(1, 20),
                'annual_output_id' => $faker->numberBetween(1, 20),
                'status' => $faker->boolean,
                'created_by' => $faker->numberBetween(1, 20),
                'updated_by' => $faker->numberBetween(1, 20),
                'created_at' => $faker->dateTime,
                'updated_at' => $faker->dateTime,
                'ip_address' => $faker->ipv4,
            ]);
        }
    }
}
