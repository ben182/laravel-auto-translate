<?php

namespace Ben182\AutoTranslate\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Themsaid\Langman\Manager;
use AutoTranslate;

class AllCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'autotrans:all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $targetLanguages = Arr::wrap(config('auto-translate.target_language'));

        foreach ($targetLanguages as $targetLanguage) {
            $translated = AutoTranslate::translate($targetLanguage, AutoTranslate::getSourceTranslations());

            AutoTranslate::fillLanguageFiles($targetLanguage, $translated);
        }
    }
}
