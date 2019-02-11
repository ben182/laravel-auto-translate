<?php

/*
 * You can place your custom package configuration in here.
 */
return [
    'source_language' => 'en',
    'target_language' => ['de'],
    'translator' => \Ben182\AutoTranslate\Translators\SimpleGoogleTranslator::class,
    'path' => realpath(base_path('resources/lang')),
];
