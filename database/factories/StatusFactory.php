<?php

use Faker\Generator as Faker;

$factory->define(App\Status::class, function (Faker $faker) {
    return [
        'content' => $faker->text,
        'user_id' => App\User::all()->random()->id,
        'tomato'=>$faker->randomElement($array = array(1,4,5,7,8)),
        'rating'=>$faker->randomElement($array = array(1,2,3,4,5)),
    ];
});
