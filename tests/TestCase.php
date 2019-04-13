<?php

namespace Ben182\AutoTranslate\Tests;

use Themsaid\Langman\LangmanServiceProvider;
use Ben182\AutoTranslate\AutoTranslateServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        exec('rm -rf '.__DIR__.'/temp/*');
    }

    public function tearDown(): void
    {
        parent::tearDown();
        exec('rm -rf '.__DIR__.'/temp/*');
    }

    public function createTempFiles($files = [])
    {
        foreach ($files as $dir => $dirFiles) {
            mkdir(__DIR__.'/temp/'.$dir);
            foreach ($dirFiles as $file => $content) {
                if (is_array($content)) {
                    mkdir(__DIR__.'/temp/'.$dir.'/'.$file);
                    foreach ($content as $subDir => $subContent) {
                        mkdir(__DIR__.'/temp/vendor/'.$file.'/'.$subDir);
                        foreach ($subContent as $subFile => $subsubContent) {
                            file_put_contents(__DIR__.'/temp/'.$dir.'/'.$file.'/'.$subDir.'/'.$subFile.'.php', $subsubContent);
                        }
                    }
                } else {
                    file_put_contents(__DIR__.'/temp/'.$dir.'/'.$file.'.php', $content);
                }
            }
        }
    }

    public function getContentOfLanguageFile($file)
    {
        return (array) include $this->app['config']['auto-translate.path'].'/'.$file;
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('auto-translate.source_language', 'en');
        $app['config']->set('auto-translate.target_language', 'de');
        $app['config']->set('auto-translate.path', realpath(__DIR__.'/temp'));
    }

    protected function getPackageProviders($app)
    {
        return [
            LangmanServiceProvider::class,
            AutoTranslateServiceProvider::class,
        ];
    }
}
