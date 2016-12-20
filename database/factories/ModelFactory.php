<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Band::class, function (Faker\Generator $faker) {

    return [
        'name' => $faker->company,
        'start_date' => $faker->date,
        'website' => "http://".$faker->domainName(),
        'still_active' => $faker->boolean(75)
    ];
});

$factory->define(App\Album::class, function (Faker\Generator $faker) {

	$release = $faker->date();

    return [
    	'band_id' => App\Band::orderBy(DB::raw('RAND()'))->first()->pluck('id'),
        'name' => $faker->name,
        'release_date' => $release,
        'recorded_date' => $faker->date('Y-m-d', $release),
    	'number_of_tracks' => rand(1,13),
    	'label'=> $faker->company,
    	'producer'=> $faker->company,
    	'genre'=> App\Album::$genres[rand(0,count(App\Album::$genres)-1)],
    ];
});
