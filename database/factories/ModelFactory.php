<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;
    static $name;

    $userName = $name ?: $faker->unique()->name;

    return [
        'name' => $userName,
        'email' => $faker->unique()->safeEmail,
        'slug' => str_slug($userName),
        'password' => $password ?: $password = bcrypt('secret'),
        'bio' => $faker->sentence(rand(5, 10)),
        'avatar' => null,
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Article::class, function (Faker\Generator $faker) {
    $tile = $faker->realText(50);
    $date = $faker->dateTime;

    return [
        'slug'      => str_slug($tile, '-'),
        'title'     => $tile,
        'body'      => $faker->realText(500),
        'subtitle'  => $faker->realText(50),
        'published' => $faker->boolean(90),
        'created_at' => $date,
        'updated_at' => $date
    ];
});

$factory->define(App\Comment::class, function (Faker\Generator $faker) {
    $date = $faker->dateTime;

    return [
        'user_id'    => $faker->numberBetween(1, 9),
        'article_id' => $faker->numberBetween(1, 9),
        'body'       => $faker->realText(100),
        'created_at' => $date,
        'updated_at' => $date,
    ];
});

$factory->define(App\Contact::class, function (Faker\Generator $faker) {
    $date = $faker->dateTime;
    $userProbability = $faker->boolean(33);

    $userID = $userProbability ? $faker->numberBetween(1, \App\User::count()) : null;
    $user = $userProbability ? \App\User::find($userID) : false;

    return [
        'user_id' => $userID,
        'name' => $userProbability ? $user->name : $faker->unique()->name,
        'email' => $userProbability ? $user->email : $faker->unique()->safeEmail,
        'message' => $faker->realText(300),
        'ip' => 'localhost',
        'user_agent' => 'database seeder',
        'created_at' => $date,
        'updated_at' => $date,
        'read' => false,
    ];
});
