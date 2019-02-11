<?php

namespace Ben182\AutoTranslate\Tests;

use Illuminate\Support\Arr;

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

    public function test_array_undot() {
        $test = [
            'test' => [
                'test' => [
                    'first' => 'test',
                    'second' => 'test',
                ]
            ]
        ];

        $dotted = Arr::dot($test);

        $this->assertEquals([
            "test.test.first" => "test",
            "test.test.second" => "test",
        ], $dotted);

        $this->assertEquals($test, app('auto-translate')->array_undot($dotted));
    }
}
