<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class WebUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        DB::table("users")->truncate();

        DB::table('users')->insert([
            'first_name' => 'System',
            'last_name' => 'Admin',
            'userName' => 'admin@gmail.com',
            'email' => 'admin@gmail.com',
            'phone' => '880123456789',
            'password' => Hash::make('123456'),
            'account_type' => '1',
            'status' => '1',
            'is_admin' => '1',
            'is_verified' => '1',
        ]);

        foreach (range(1, 25) as $index) {
            DB::table('users')->insert([
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'userName' => 'user'.$index,
                'email' => 'email'.$index.'@test.com',
                'phone' => $faker->phoneNumber,
                'password' => Hash::make('123456'),
                'account_type' => $faker->numberBetween(1,2),
                'is_admin' => '0',
                'is_verified' => '1',
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
