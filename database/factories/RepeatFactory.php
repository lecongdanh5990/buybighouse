<?php

use Faker\Generator as Faker;
use App\Repeat;
use App\Target;

$factory->define(Repeat::class, function (Faker $faker) {
    return [
        'repeat' => $faker->randomElement($array = array(0,1)),
        'target_id' => factory(Target::class)->create()->id,
    ];
});
