<?php

use App\Model\Country;
use App\Model\Division;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class DivisionTableSeeder extends Seeder
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
        DB::table("divisions")->truncate();
        $countries = collect(json_decode(
            file_get_contents(
                'https://raw.githubusercontent.com/hiiamrohit/Countries-States-Cities-database/master/countries.json'
            )
        )->countries);
        $states = file_get_contents(
            'https://raw.githubusercontent.com/hiiamrohit/Countries-States-Cities-database/master/states.json'
        );
        $states = collect(json_decode($states)->states);
        $states->map(function ($state) use ($countries,$faker) {
            $countryIDFinder = $countries->first(function ($cn) use ($state) {
                return $state->country_id == $cn->id;
            });
            $existCountry = Country::whereCode($countryIDFinder->sortname)->first();
            Division::updateOrCreate([
                'name' => $state->name
            ], [
                'name' => $state->name,
                'country_id' => optional($existCountry)->id ?? 1,
                'status' => $faker->randomElement([1,2,3]),
                'created_by' => $faker->randomElement([1,2,3]),
                'updated_by' => $faker->randomElement([1,2,3]),
                'created_at' => $faker->dateTime,
                'updated_at' => $faker->dateTime,
                'ip_address' => $faker->ipv4,
            ]);
        });
    }
}
