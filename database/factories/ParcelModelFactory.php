<?php

use App\Models\v1\Parcel;

$factory->define(Parcel::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->sentence(2),
        'weight' => $faker->randomFloat(2, 0, 10),
        'volume' => $faker->randomFloat(5, 0, 0.001),
        'value' => $faker->randomFloat(0, 1, 1000),
        'model' => $faker->randomElement([MODEL_BY_WEIGHT, MODEL_BY_VOLUME, MODEL_BY_VALUE]),
        'quote' => $faker->randomFloat(2)
    ];
});

