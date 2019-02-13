<?php

return [
    /**
     * Here you can specify the source language code.
     */
    'source_language' => 'en',

    /**
     * Here you can specify the target language code(s). This can be a string or an array.
     */
    'target_language' => ['de'],

    /**
     * Specify the path to the translation files.
     */
    'path' => realpath(base_path('resources/lang')),

    /**
     * This is the translator used to translate the source language files. You can also specify your own here if you wish. It has to implement \Ben182\AutoTranslate\Translators\TranslatorInterface.
     */
    'translator' => \Ben182\AutoTranslate\Translators\SimpleGoogleTranslator::class,
];
