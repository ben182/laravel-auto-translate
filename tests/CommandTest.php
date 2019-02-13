<?php

namespace Ben182\AutoTranslate\Tests;

use Ben182\AutoTranslate\AutoTranslateFacade;
use Ben182\AutoTranslate\Commands\AllCommand;
use Ben182\AutoTranslate\Commands\MissingCommand;

class CommandTest extends TestCase
{
    public function test_AllCommand()
    {
        $this->createTempFiles([
            'en' => [
                'user' => "<?php\n return ['name' => 'Name', 'age' => 'Age'];",
            ],
        ]);

        try {
            (new AllCommand(app('auto-translate')))->handle();
        } catch (\Throwable $th) {
        }

        $translations = AutoTranslateFacade::getTranslations('de');

        $this->assertEquals([
            'user' => [
                'name' => 'Name',
                'age' => 'Alter',
            ],
        ], $translations);
    }

    public function test_MissingCommand()
    {
        $this->createTempFiles([
            'en' => [
                'user' => "<?php\n return ['name' => 'Name', 'age' => 'Age'];",
            ],
            'de' => [
                'user' => "<?php\n return ['name' => 'Name'];",
            ],
        ]);

        try {
            (new MissingCommand(app('auto-translate')))->handle();
        } catch (\Throwable $th) {
        }

        $translations = AutoTranslateFacade::getTranslations('de');

        $this->assertEquals([
            'user' => [
                'name' => 'Name',
                'age' => 'Alter',
            ],
        ], $translations);
    }
}
