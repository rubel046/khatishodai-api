<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use App\User;


class CompanyTableSeeder extends Seeder
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
            DB::table('company_basic_infos')->insert([
                'user_id' => User::all()->random()->id,
                'name' => $faker->company,
                'display_name' => $faker->text(20),
                'establishment_date' => $faker->dateTime(20),
                'office_space' => $faker->text(20),
                'operation_address' => $faker->text(20),
                'website' => $faker->text(20),
                'email' => $faker->text(20),
                'phone' => $faker->text(20),
                'cell' => $faker->text(20),
                'fax' => $faker->text(20),
                'number_of_employee' => $faker->text(20),
                'ownership_type' => $faker->text(20),
                'turnover_id' => $faker->numberBetween(1, 20),
                'status' => $faker->boolean(),
                'created_by' => $faker->numberBetween(1, 20),
                'updated_by' => $faker->numberBetween(1, 20),
                'created_at' => $faker->dateTime,
                'updated_at' => $faker->dateTime,
                'ip_address' => $faker->ipv4,
            ]);
        }
    }
}
