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
            'account_type' => '1',
            'status' => '1',
            'userName' => 'admin@gmail.com',
            'email' => 'admin@gmail.com',
            'phone' => '880123456789',
            'password' => Hash::make('123456'),
        ]);
    }
}
