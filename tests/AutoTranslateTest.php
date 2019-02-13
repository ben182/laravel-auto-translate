<?php

namespace Ben182\AutoTranslate\Tests;

use Mockery;
use Illuminate\Support\Arr;
use Ben182\AutoTranslate\Translators\TranslatorInterface;
use Ben182\AutoTranslate\AutoTranslateFacade;

class AutoTranslateTest extends TestCase
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
        ], AutoTranslateFacade::getTranslations('en'));

        $this->assertEquals([
            'user' => [
                'name' => 'Name',
                'age' => 'Age',
            ],
        ], AutoTranslateFacade::getSourceTranslations());
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

        $this->assertEquals($test, AutoTranslateFacade::array_undot($dotted));
    }

    public function test_getMissingTranslations()
    {
        $this->createTempFiles([
            'en' => [
                'user' => "<?php\n return ['name' => 'Name', 'age' => 'Age'];",
                'dd' => "<?php\n return ['name' => 'Name'];",
            ],
            'de' => [
                'user' => "<?php\n return ['name' => 'Name', 'age' => 'Age'];",
            ],
        ]);

        $missing = AutoTranslateFacade::getMissingTranslations('de');

        $this->assertEquals([
            'dd.name' => 'Name',
        ], $missing->toArray());
    }

    public function test_getMissingTranslations2()
    {
        $this->createTempFiles([
            'en' => [
                'user' => "<?php\n return ['name' => 'Name', 'age' => 'Age'];",
            ],
            'de' => [
                'user' => "<?php\n return ['name' => 'Name'];",
            ],
        ]);

        $missing = AutoTranslateFacade::getMissingTranslations('de');

        $this->assertEquals([
            'user.age' => 'Age',
        ], $missing->toArray());
    }

    public function test_getMissingTranslations3()
    {
        $this->createTempFiles([
            'en' => [
                'user' => "<?php\n return ['name' => 'Name'];",
            ],
            'de' => [
                'user' => "<?php\n return ['name' => 'Name', 'age' => 'Age'];",
            ],
        ]);

        $missing = AutoTranslateFacade::getMissingTranslations('de');

        $this->assertEquals([], $missing->toArray());
    }

    public function test_fillLanguageFiles()
    {
        $test = [
            'user' => [
                'age' => 'Age',
            ],
        ];

        AutoTranslateFacade::fillLanguageFiles('en', $test);

        $translations = AutoTranslateFacade::getTranslations('en');

        $this->assertEquals($test, $translations);
    }

    public function test_translate()
    {
        $mock = Mockery::mock(TranslatorInterface::class);
        $mock
        ->shouldReceive('setSource')
        ->shouldReceive('setTarget')

        ->shouldReceive('translate')
            ->with('Age')
            ->andReturn('test')
            ->mock();

        $this->app->instance(TranslatorInterface::class, $mock);

        $translations = AutoTranslateFacade::translate('de', [
            'user' => [
                'age' => 'Age',
            ],
        ]);

        $this->assertEquals([
            'user' => [
                'age' => 'test',
            ],
        ], $translations);
    }
}
