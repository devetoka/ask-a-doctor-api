<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Reply;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(Reply::class, function (Faker $faker) {
    //replies and questions can be replied to
    $question = factory(\App\Models\Question::class)->create();
    return [
        'id' => preg_replace('/\./', '', uniqid('rpy', true)),
        'replyable_type' => get_class($question),
        'replyable_id' => $question->id,
        'user_id' => (factory(User::class)->create())->id,
        'content' => $faker->paragraph
    ];

});
