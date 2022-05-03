<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Country;
use App\Models\State;
use Illuminate\Database\Seeder;

class WorldStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $countriesArray = ['Algeria', 'Bahrain', 'Djibouti', 'Egypt', 'Iraq', 'Jordan', 'Kuwait', 'Lebanon', 'Libya', 'Morocco', 'Mauritania', 'Oman', 'Palestinian Territory Occupied', 'Qatar', 'Saudi Arabia', 'Sudan', 'Syria', 'Tunisia', 'Yemen'];
        //get all countries that has states and existing in this array countriesArray, then upate the status
        Country::whereHas('states')->whereIn('name', $countriesArray)->update(['status' => true]);

        //get all states that has cities and join it with countries using country_id,
        //then get the records that has countries.status = 1 and update the value for states.status
        State::select('states.*')
        ->whereHas('cities')
        ->join('countries', 'states.country_id', '=', 'countries.id')
        ->where(['countries.status' => 1])
        ->update(['states.status' => true]);

        //get all cities and join it with states using state_id,
        //then get the records that has states.status = 1 and update the value for cities.status
        City::select('cities.*')
        ->join('states', 'cities.state_id', '=', 'states.id')
        ->where(['states.status' => 1])
        ->update(['cities.status' => true]);

    }
}
