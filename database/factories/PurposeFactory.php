<?php

use Faker\Generator as Faker;
use Illuminate\Foundation\Auth\User;

$factory->define(App\Purpose::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'user_id' => User::all()->random()->id,
        'complete'=>$faker->randomElement($array = array(1,0)),
        'startday' => $faker->date($format = 'Y-m-d', $max = '2025-12-31'),
        'endday'=>$faker->date($format = 'Y-m-d', $max = '2025-12-31'),
        'realendday'=>$faker->date($format = 'Y-m-d', $max = '2025-12-31') ,
        
    ];
});
