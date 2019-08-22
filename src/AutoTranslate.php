<?php

namespace Ben182\AutoTranslate;

use Illuminate\Support\Arr;
use Ben182\AutoTranslate\Translators\TranslatorInterface;

abstract class AutoTranslate
{
    public $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
        $this->translator->setSource(config('auto-translate.source_language'));
    }

    public function translateAll(array $targetLanguages, $callbackAfterEachTranslation = null)
    {
        $sourceTranslations = $this->getSourceTranslations();

        foreach ($targetLanguages as $targetLanguage) {
            $translated = $this->translate(
                $targetLanguage,
                $sourceTranslations,
                $callbackAfterEachTranslation
            );

            $this->fillLanguageFiles($targetLanguage, $translated);
        }
    }

    public function translateMissing(array $targetLanguages, $callbackAfterEachTranslation = null)
    {
        foreach ($targetLanguages as $targetLanguage) {
            $missingTranslations = $this->getMissingTranslations($targetLanguage);

            $translated = $this->translate(
                $targetLanguage,
                $missingTranslations,
                $callbackAfterEachTranslation
            );

            $this->fillLanguageFiles($targetLanguage, $translated);
        }
    }

    public function translate(string $targetLanguage, $sourceTranslation, $callbackAfterEachTranslation = null)
    {
        $this->translator->setTarget($targetLanguage);

        foreach ($sourceTranslation as $key => $value) {
            $variables = $this->findVariables($value);

            $sourceTranslation[$key] = is_string($value) ? $this->translator->translate($value) : $value;

            $sourceTranslation[$key] = $this->replaceTranslatedVariablesWithOld($variables, $sourceTranslation[$key]);

            if ($callbackAfterEachTranslation) {
                $callbackAfterEachTranslation();
            }
        }

        return $sourceTranslation;
    }

    public function findVariables($string)
    {
        $m = null;

        if (is_string($string)) {
            preg_match_all('/:\S+/', $string, $m);
        }

        return $m;
    }

    public function replaceTranslatedVariablesWithOld($variables, $string)
    {
        if (isset($variables[0])) {
            $replacements = $variables[0];

            return preg_replace_callback('/:\S+/', function ($matches) use (&$replacements) {
                return array_shift($replacements);
            }, $string);
        }
    }

    abstract public function getSourceTranslations();
    abstract public function getTranslations(string $lang);
    abstract public function getMissingTranslations(string $lang);
    abstract public function fillLanguageFiles(string $language, array $data);
}
