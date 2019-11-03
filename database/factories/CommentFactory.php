<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Topic;
use App\Moods;
use App\Comment;

$factory->define(Topic::class, function (Faker\Generator $faker) {
    return [
        'title' => $faker->sentence,
        'body' => $faker->text,
    ];
});

$factory->define(Moods::class, function (Faker\Generator $faker) {

    return [
        'title' => $faker->sentence,
        'url' => $faker->url,
    ];
});

$factory->define(Comment::class, function (Faker\Generator $faker) {
    return [
        'body' => $faker->text,
        'commentable_id' => factory(Post::class)->create()->id,
        'commentable_type' => Post::TABLE,
    ];

//    return [
//        'body' => $faker->text,
//        'commentable_id' => factory(Video::class)->create()->id,
//        'commentable_type' => Video::TABLE,
//    ];
});