<?php

use Faker\Generator as Faker;
use Ygg\Resource\Models\Taxonomy;

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

$factory->define(Taxonomy::class, function (Faker $faker) {
    $taxonomy = $faker->randomElement($array = ['category', 'goods']);
    $parent = Taxonomy::where('taxonomy', $taxonomy)->get()->count();
    $parent_id = ($parent > 0) ? Taxonomy::where('taxonomy', $taxonomy)->inRandomOrder()->first()->id : null;

    return [
        'taxonomy'  => $taxonomy,
        'parent_id' => $faker->randomElement($array = [null, $parent_id]),
    ];
});
