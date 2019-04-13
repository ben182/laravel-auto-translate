<?php

namespace Ben182\AutoTranslate\Commands;

use Illuminate\Support\Arr;
use Illuminate\Console\Command;
use Ben182\AutoTranslate\AutoTranslate;

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
    protected $description = 'Translates all source translations that are not set in your target translations';

    protected $autoTranslator;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(AutoTranslate $autoTranslator)
    {
        parent::__construct();
        $this->autoTranslator = $autoTranslator;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $targetLanguages = Arr::wrap(config('auto-translate.target_language'));

        $this->line('Found '.count($targetLanguages).' languages to translate');

        $bar = $this->output->createProgressBar(count($targetLanguages));
        $bar->start();

        $missingCount = 0;

        foreach ($targetLanguages as $targetLanguage) {
            $missing = $this->autoTranslator->getMissingTranslations($targetLanguage);
            $missingCount += $missing->count();

            $translated = $this->autoTranslator->translate($targetLanguage, $missing);

            $this->autoTranslator->fillLanguageFiles($targetLanguage, $translated);

            $bar->advance();
        }

        $bar->finish();

        $this->info('Translated '.$missingCount.' missing language keys.');
    }
}
