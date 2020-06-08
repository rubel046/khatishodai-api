<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use App\Model\Company;

class CompanyDetailTableSeeder extends Seeder
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
            DB::table('company_details')->insert([
                'company_id' => Company::all()->random()->id,
                'logo' => $faker->imageUrl(),
                'about_us' => $faker->text(100),
                'mission' => $faker->text(100),
                'vision' => $faker->text(100),
                'youtube_link' => $faker->url,
                'fb_link' => $faker->url,
                'status' => $faker->boolean,
                'created_by' => $faker->numberBetween(1,20),
                'updated_by' => $faker->numberBetween(1,20),
                'created_at' => $faker->dateTime,
                'updated_at' => $faker->dateTime,
                'ip_address' => $faker->ipv4,
            ]);
        }
    }
}
