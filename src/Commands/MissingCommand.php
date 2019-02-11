<?php

namespace Ben182\AutoTranslate\Commands;

use AutoTranslate;
use Illuminate\Support\Arr;
use Illuminate\Console\Command;

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

        $missingCount = 0;

        foreach ($targetLanguages as $targetLanguage) {
            $missing = AutoTranslate::getMissingTranslations($targetLanguage);

            $missingCount += $missing->count();

            $translated = AutoTranslate::translate($targetLanguage, $missing);

            AutoTranslate::fillLanguageFiles($targetLanguage, $translated);
        }

        $this->info('Found ' . $missingCount . ' missing language keys.');
    }
}
