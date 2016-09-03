<?php

use JeroenNoten\LaravelPoll\Models\PollAnswer;
use JeroenNoten\LaravelPoll\Models\PollQuestion;

$factory->define(PollQuestion::class, function (Faker\Generator $faker) {
    return [
        'text' => $faker->text,
    ];
});

$factory->define(PollAnswer::class, function (Faker\Generator $faker) {
    return [
        'text'             => $faker->text,
        'poll_question_id' => function () {
            return factory(PollQuestion::class)->create()->id;
        },
    ];
});
