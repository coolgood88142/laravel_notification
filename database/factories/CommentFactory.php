<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Models\Comment;
use Faker\Generator as Faker;

$factory->define(Comment::class, function (Faker $faker) {
    return [
        'articles_id' => 1,
        'user_id' => 1,
        'text' => '測試新增留言',
    ];
});
