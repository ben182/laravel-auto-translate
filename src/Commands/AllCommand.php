<?php

namespace Ben182\AutoTranslate\Commands;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Ben182\AutoTranslate\AutoTranslators\JsonTranslator;
use Ben182\AutoTranslate\AutoTranslators\PhpTranslator;

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
    protected $description = 'Translates all source translations to target translations';

    protected $phpTranslator;
    protected $jsonTranslator;

    protected $targetLanguages;

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
    private function translatePhpFiles()
    {
        $this->line('Translating PHP files');

        $sourceTranslations = $this->phpTranslator->getSourceTranslations();
        $availableTranslations = count($sourceTranslations) * count($this->targetLanguages);

        $bar = $this->output->createProgressBar($availableTranslations);
        $bar->start();

        $this->phpTranslator->translateAll($this->targetLanguages, function () use ($bar) {
            $bar->advance();
        });

        $bar->finish();

        $this->info("\nTranslated $availableTranslations language keys.");
    }

    private function translateJsonFiles()
    {
        $sourceTranslations = $this->jsonTranslator->getSourceTranslations();

        if (count($sourceTranslations) === 0) {
            $this->line('No JSON files to translate!');
            return;
        }

        $this->line('Translating JSON files');

        $availableTranslations = count($sourceTranslations) * count($this->targetLanguages);

        $bar = $this->output->createProgressBar($availableTranslations);
        $bar->start();

        $this->jsonTranslator->translateAll($this->targetLanguages, function () use ($bar) {
            $bar->advance();
        });

        $bar->finish();

        $this->info("\nTranslated $availableTranslations language keys.");
    }
}
