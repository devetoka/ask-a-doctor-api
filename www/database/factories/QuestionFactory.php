<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Models\Category;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(\App\Models\Question::class, function (Faker $faker) {
    $category = factory(Category::class)->create();
    $user = factory(\App\Models\User::class)->create();
    $title = $faker->sentence;
    return [
        'category_id' => $category->id,
        'user_id' => $user,
        'title' => $title,
        'slug' => Str::slug($title),
        'description' => $faker->paragraph
    ];
});
