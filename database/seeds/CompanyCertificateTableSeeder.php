<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use App\Model\Company;

class CompanyCertificateTableSeeder extends Seeder
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
            DB::table('company_certificates')->insert([
                'company_id' => Company::all()->random()->id,
                'name' => $faker->company,
                'reference_number' => $faker->text(10),
                'issued_by' => $faker->numberBetween(1, 20),
                'start_date' => $faker->dateTime,
                'end_date' => $faker->dateTime,
                'certificate_photo_name' => $faker->imageUrl(),
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
