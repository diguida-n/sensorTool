<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'remember_token' => str_random(10),
        'enterprise_id' => function (){
        	return factory('App\Models\Enterprise')->create()->id;
        },
        'site_id' => function (){
        	return factory('App\Models\Site')->create()->id;
        },
    ];
});

$factory->define(App\Models\Enterprise::class, function (Faker $faker) {
    return [
        'businessName' => $faker->name,
        'address' => $faker->address,
        'vatNumber' => $faker->vat,
    ];
});

$factory->define(App\Models\Site::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'address' => $faker->address,
        'map' => $faker->vat,
        'description' => $faker->text,
        'enterprise_id' => function (){
        	return factory('App\Models\Enterprise')->create()->id;
        },
        'site_type_id' => function (){
        	return factory('App\Models\SiteType')->create()->id;
        },
    ];
});

$factory->define(App\Models\SiteType::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
    ];
});