<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Models\Channels;
use Faker\Generator as Faker;

$factory->define(Channels::class, function (Faker $faker) {
    return [
        'name' => '測試頻道',
    ];
});
