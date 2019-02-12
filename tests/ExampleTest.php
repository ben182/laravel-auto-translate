<?php

namespace Ben182\AutoTranslate\Tests;

use Illuminate\Support\Arr;
use Mockery;
use Ben182\AutoTranslate\Translators\SimpleGoogleTranslator;
use Ben182\AutoTranslate\Translators\TranslatorInterface;

class ExampleTest extends TestCase
{
    public function test_getTranslations()
    {
        $this->createTempFiles([
            'en' => [
                'user' => "<?php\n return ['name' => 'Name', 'age' => 'Age'];",
            ],
        ]);

        $this->assertEquals([
            'name' => 'Name',
            'age' => 'Age',
        ], $this->getContentOfLanguageFile('en/user.php'));

        $this->assertEquals([
            'user' => [
                'name' => 'Name',
                'age' => 'Age',
            ],
        ], app('auto-translate')->getTranslations('en'));

        $this->assertEquals([
            'user' => [
                'name' => 'Name',
                'age' => 'Age',
            ],
        ], app('auto-translate')->getSourceTranslations());
    }

    public function test_array_undot()
    {
        $test = [
            'test' => [
                'test' => [
                    'first' => 'test',
                    'second' => 'test',
                ],
            ],
        ];

        $dotted = Arr::dot($test);

        $this->assertEquals([
            'test.test.first' => 'test',
            'test.test.second' => 'test',
        ], $dotted);

        $this->assertEquals($test, app('auto-translate')->array_undot($dotted));
    }

    public function test_getMissingTranslations() {

        $this->createTempFiles([
            'en' => [
                'user' => "<?php\n return ['name' => 'Name', 'age' => 'Age'];",
                'dd' => "<?php\n return ['name' => 'Name'];",
            ],
            'de' => [
                'user' => "<?php\n return ['name' => 'Name', 'age' => 'Age'];",
            ],
        ]);

        $missing = app('auto-translate')->getMissingTranslations('de');

        $this->assertEquals([
            'dd.name' => 'Name',
        ], $missing->toArray());
    }

    public function test_getMissingTranslations2() {

        $this->createTempFiles([
            'en' => [
                'user' => "<?php\n return ['name' => 'Name', 'age' => 'Age'];",
            ],
            'de' => [
                'user' => "<?php\n return ['name' => 'Name'];",
            ],
        ]);

        $missing = app('auto-translate')->getMissingTranslations('de');

        $this->assertEquals([
            'user.age' => 'Age',
        ], $missing->toArray());
    }

    public function test_getMissingTranslations3() {

        $this->createTempFiles([
            'en' => [
                'user' => "<?php\n return ['name' => 'Name'];",
            ],
            'de' => [
                'user' => "<?php\n return ['name' => 'Name', 'age' => 'Age'];",
            ],
        ]);

        $missing = app('auto-translate')->getMissingTranslations('de');

        $this->assertEquals([], $missing->toArray());
    }

    public function test_fillLanguageFiles() {
        $test = [
            'user' => [
                'age' => 'Age',
            ],
        ];

        app('auto-translate')->fillLanguageFiles('en', $test);

        $translations = app('auto-translate')->getTranslations('en');

        $this->assertEquals($test, $translations);
    }

    public function test_translate() {
        $mock = Mockery::mock(TranslatorInterface::class);
        $mock
        ->shouldReceive('setSource')
        ->shouldReceive('setTarget')

        ->shouldReceive('translate')
            ->with('Age')
            ->andReturn('test')
            ->mock();
        $this->app->instance(TranslatorInterface::class, $mock);

        $translations = app('auto-translate')->translate('de', [
            'user' => [
                'age' => 'Age',
            ]
        ]);

        $this->assertEquals([
            'user' => [
                'age' => 'test',
            ],
        ], $translations);
    }
}
