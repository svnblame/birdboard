<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Task;
use App\Project;
use Faker\Generator as Faker;

$factory->define(Task::class, function (Faker $faker) {
    return [
    	'project_id' => factory(\App\Project::class),
        'body' => $faker->sentence(),
        'completed' => false,
    ];
});
