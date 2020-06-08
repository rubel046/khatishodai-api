<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class AccountTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $typeArray=['Supplier','Buyer','Both Supplier and Buyer','Super Admin', 'Admin','Service Provider','Sales Assistant','Product Manager'];
        foreach ($typeArray as $val) {
            DB::table('account_types')->insert([
                'name' => $val,
                'description' => $faker->text(50),
                'is_admin' => $faker->boolean(),
                'status' => $faker->boolean(),
                'created_by' => $faker->randomElement([1,2,3]),
                'updated_by' => $faker->randomElement([1,2,3]),
                'created_at' => $faker->dateTime,
                'updated_at' => $faker->dateTime,
                'ip_address' => $faker->ipv4,
            ]);
        }
    }
}
