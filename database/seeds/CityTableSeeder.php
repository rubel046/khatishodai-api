<?php

use App\Model\Division;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class CityTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        Schema::disableForeignKeyConstraints();
        DB::table('cities')->truncate();
        $states = file_get_contents(
            'https://raw.githubusercontent.com/hiiamrohit/Countries-States-Cities-database/master/states.json'
        );
        $states = collect(json_decode($states)->states);
        $fileCities = file_get_contents(base_path('cities.json'));
        $cities = collect(json_decode($fileCities));
        $cities->map(function ($city) use ($states, $faker) {
            $stateIdFinder = $states->firstWhere('id', '=', optional($city)->state_id);
            $state = Division::whereName(optional($stateIdFinder)->name)->first();
            if ($state) {
                DB::table('cities')->insert([
                    'name' => optional($city)->name,
                    'division_id' => optional($state)->id,
                    'status' => $faker->boolean,
                    'created_by' => $faker->randomElement([1, 2, 3]),
                    'updated_by' => $faker->randomElement([1, 2, 3]),
                    'created_at' => $faker->dateTime,
                    'updated_at' => $faker->dateTime,
                    'ip_address' => $faker->ipv4,
                ]);
            }

        });


    }
}
