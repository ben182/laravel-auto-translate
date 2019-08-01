<h6 align="center">
    <img src="https://i.ibb.co/5hhcPSH/defile-de-mode-1.png" width="300"/>
</h6>

<p align="center"><a href="https://github.com/ben182/laravel-auto-translate/releases"><img src="https://camo.githubusercontent.com/7aeaaffdab372bb7f1a7bc771400d9e18295916b/68747470733a2f2f696d672e736869656c64732e696f2f6769746875622f72656c656173652f62656e3138322f6c61726176656c2d6175746f2d7472616e736c6174652e7376673f7374796c653d666c61742d737175617265" alt="Latest Version" data-canonical-src="https://img.shields.io/github/release/ben182/laravel-auto-translate.svg?style=flat-square" style="max-width:100%;"></a>
<a href="https://travis-ci.org/ben182/laravel-auto-translate" rel="nofollow"><img src="https://camo.githubusercontent.com/8c01aa130a16fabf6a8e313719f4f274c7c401b4/68747470733a2f2f696d672e736869656c64732e696f2f7472617669732f62656e3138322f6c61726176656c2d6175746f2d7472616e736c6174652f6d61737465722e7376673f7374796c653d666c61742d737175617265" alt="Build Status" data-canonical-src="https://img.shields.io/travis/ben182/laravel-auto-translate/master.svg?style=flat-square" style="max-width:100%;"></a>
<a href="https://scrutinizer-ci.com/g/ben182/laravel-auto-translate" rel="nofollow"><img src="https://camo.githubusercontent.com/a2132ab348aaaeae4e0cfee432965a86b8d6b7af/68747470733a2f2f696d672e736869656c64732e696f2f7363727574696e697a65722f672f62656e3138322f6c61726176656c2d6175746f2d7472616e736c6174652e7376673f7374796c653d666c61742d737175617265" alt="Quality Score" data-canonical-src="https://img.shields.io/scrutinizer/g/ben182/laravel-auto-translate.svg?style=flat-square" style="max-width:100%;"></a></p>

With this package you can translate your language files using a translator service. The package ships with a free Google Translate version and Deepl.

Specify a source language and a target language and it will automatically translate your files. This is useful if you want to prototype something quickly or just a first idea of the translation for later editing. The package ships with two artisan commands. One for translating all the missing translations that are set in the source language but not in the target language. The other one for translating all source language files and overwriting everything in the target language.

## Installation

This package can be used in Laravel 5.6 or higher and needs PHP 7.2 or higher.

You can install the package via composer:

```bash
composer require ben182/laravel-auto-translate
```

## Config

After installation publish the config file:

```bash
php artisan vendor:publish --provider="Ben182\AutoTranslate\AutoTranslateServiceProvider"
```

You can specify your source language, the target language(s), the translator and the path to your language files in there.

## Translators

| Name                   | Free | File                                                    | Documentation                       | Available languages |
|------------------------|------|---------------------------------------------------------|-------------------------------------|----------|
| Google Translate HTTP  | Yes  | Ben182\AutoTranslate\Translators\SimpleGoogleTranslator | /                                   | Over 100 |
| Google Cloud Translate | No   | Ben182\AutoTranslate\Translators\GoogleCloudTranslator  | [Documentation](https://cloud.google.com/translate/) | Over 100 |
| Deepl API v2           | No   | Ben182\AutoTranslate\Translators\DeeplTranslator        | [Documentation](https://www.deepl.com/docs-api.html) | EN, DE, FR, ES, PT, IT, NL, PL, RU |

If you have lots of translations to make I recommend Google Cloud Translate or Deepl. They are fast, reliable and you will not encounter any rate limiting.

## Usage

### Missing translations

Simply call the artisan missing command for translating all the translations that are set in your source language, but not in your target language:

```bash
php artisan autotrans:missing
```

E.g. you have English set as your source language. The source language has translations in auth.php:

```php
<?php

return [
    'failed' => 'These credentials do not match our records.',
    'throttle' => 'Too many login attempts. Please try again in :seconds seconds.',
];
```

Your target language is German. The auth.php file has the following translations:

```php
<?php

return [
    'failed' => 'Diese Kombination aus Zugangsdaten wurde nicht in unserer Datenbank gefunden.',
];
```

The artisan missing command will then translate the missing `auth.throttle` key.

### All translations

To overwrite all your existing target language keys with the translation of the source language simply call:

```bash
php artisan autotrans:all
```

This will overwrite every single key with a translation of the equivalent source language key.

### Parameters

Sometimes you have translations like these:

```php
'welcome' => 'Welcome, :name',
```

They can be replaced with variables. When we pass these placeholders to a translator service, weird things can happen. Sometimes the placeholder comes back in upper-case letters or it has been translated. Thankfully the package will respect your variable placeholders, so they will be the same after the translation.

## Extending

You can create your own translator by creating a class that implements `\Ben182\AutoTranslate\Translators\TranslatorInterface`. Simply reference it in your config file.

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email moin@benjaminbortels.de instead of using the issue tracker.

## Credits

- [Benjamin Bortels](https://github.com/ben182)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).
