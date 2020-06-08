<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
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

    }
}
