<?php

use Faker\Generator as Faker;
use App\Purpose;

$factory->define(App\Target::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'purpose_id' => Purpose::all ()->random()->id,
        'ontime'=>$faker-> randomElement($array = array(0,1)),
        'repeat' => $faker->randomElement($array = array(0, 1)),
        'last_completed_day'=>$faker-> date($format = 'Y-m-d', $max = 'now'),
        'last_date_to_do' => $faker->date($format = 'Y-m-d', $max = 'now'),
        
        'time_to_complete'=>$faker->numberBetween($min = 10, $max = 20),
        'time_has_completed'=>$faker-> numberBetween($min = 1, $max = 10),
        'complete'=>$faker-> randomElement($array = array(0,1)),
        
    ];
});
