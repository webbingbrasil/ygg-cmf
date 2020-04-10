<?php

use Cviebrock\EloquentSluggable\Services\SlugService;
use Faker\Generator as Faker;
use Ygg\Resource\Models\Resource;

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

$factory->define(Resource::class, function (Faker $faker) {
    $name = $faker->sentence(6);

    return [
        'type'       => 'example-post',
        'status'     => 'publish',
        'content'    => [
            'en' => [
                'name'        => $name,
                'title'       => $faker->sentence(6),
                'description' => $faker->paragraph(2),
                'body'        => $faker->text,
                'body2'       => $faker->text,
                'picture'     => $faker->imageUrl(640, 480),
                'open'        => $faker->dateTimeBetween('-30 years', 'now')->format('Y-m-d H:i:s'),
                'robot'       => $faker->randomElement(['noindex']),
                'block'       => $faker->text,
                'keywords'    => implode(',', $faker->words(5)),
                'list'        => $faker->words(5),
            ],
        ],
        'publish_at' => $faker->date('Y-m-d H:i:s'),
        'options'    => [
            'locale' => [
                'en' => 'true',
            ],
        ],
        'slug'       => SlugService::createSlug(Resource::class, 'slug', $name),
    ];
});
