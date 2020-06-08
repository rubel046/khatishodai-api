<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use App\Model\Company;

class CompanyTradeMembershipTableSeeder extends Seeder
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
            DB::table('company_trade_memberships')->insert([
                'company_id' => Company::all()->random()->id,
                'name' => $faker->text(18),
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
