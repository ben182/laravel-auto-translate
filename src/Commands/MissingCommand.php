<?php

namespace Ben182\AutoTranslate\Commands;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Ben182\AutoTranslate\AutoTranslators\JsonTranslator;
use Ben182\AutoTranslate\AutoTranslators\PhpTranslator;

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

    protected $phpTranslator;
    protected $jsonTranslator;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(PhpTranslator $phpTranslator, JsonTranslator $jsonTranslator)
    {
        parent::__construct();
        $this->phpTranslator = $phpTranslator;
        $this->jsonTranslator = $jsonTranslator;
        $this->targetLanguages = Arr::wrap(config('auto-translate.target_language'));
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $numTargetLanguages = count($this->targetLanguages);

        $this->line(implode(' ', [
            'Found',
            $numTargetLanguages,
            Str::plural('language', $numTargetLanguages),
            'to translate',
        ]));

        $this->translatePhpFiles();
        $this->translateJsonFiles();
    }

    public function translatePhpFiles()
    {
        $this->line('Translating PHP files');

        $missingCount = 0;
        foreach ($this->targetLanguages as $targetLanguage) {
            $missingTranslations = $this->phpTranslator->getMissingTranslations($targetLanguage);
            $missingCount += count($missingTranslations);

            $this->line(implode(' ', [
                'Found',
                count($missingTranslations),
                'missing keys in',
                $targetLanguage,
            ]));
        }

        $bar = $this->output->createProgressBar($missingCount);
        $bar->start();

        $this->phpTranslator->translateMissing($this->targetLanguages, function () use ($bar) {
            $bar->advance();
        });

        $bar->finish();

        $this->info("\nTranslated ".$missingCount.' missing language keys.');
    }

    public function translateJsonFiles()
    {
        $this->line('Translating JSON files');

        $missingCount = 0;
        foreach ($this->targetLanguages as $targetLanguage) {
            $missingTranslations = $this->jsonTranslator->getMissingTranslations($targetLanguage);
            $missingCount += count($missingTranslations);

            $this->line(implode(' ', [
                'Found',
                count($missingTranslations),
                'missing keys in',
                $targetLanguage,
            ]));
        }

        $bar = $this->output->createProgressBar($missingCount);
        $bar->start();

        $this->jsonTranslator->translateMissing($this->targetLanguages, function () use ($bar) {
            $bar->advance();
        });

        $bar->finish();

        $this->info("\nTranslated ".$missingCount.' missing language keys.');
    }
}
