<?php

use Faker\Generator as Faker;

$factory->define(App\Url::class, function (Faker $faker) {
	$base = collect(str_split("azertyuiopqsdfghjklmwxcvbnAZERTYUIOPQSDFGHJKLMWXCVBN1234567890-_"));
    $slug = '';
    for ($i = 0; $i < 7; $i++) {
        $slug .= $base->random();
    }
    return [
        'url' => trim(preg_replace('/^www./', '', preg_replace('/^https?:\/\//', '', $faker->url)), '/'),
        'slug' => $slug
    ];
});
