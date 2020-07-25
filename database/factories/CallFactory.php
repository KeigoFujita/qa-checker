<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Call;
use Carbon\Carbon;
use Faker\Generator as Faker;


$factory->define(Call::class, function (Faker $faker) {
    $duration = rand(60, 900);
    $date = Carbon::parse($faker->dateTimeBetween('-30 days', 'now'));
    return [
        'rating' => rand(3, 5),
        'duration' => $duration,
        'amount_earned' => round($duration * (0.20 / 60), 2),
        'submitted_at' => $date->format('Y-m-d')
    ];
});