<?php

namespace Ben182\AutoTranslate\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use AutoTranslate;

class MissingCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'autotrans:missing';

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
            // dump(AutoTranslate::getMissingTranslations($targetLanguage));
            $translated = AutoTranslate::translate($targetLanguage, AutoTranslate::getMissingTranslations($targetLanguage));

            AutoTranslate::fillLanguageFiles($targetLanguage, $translated);
        }
    }
}
