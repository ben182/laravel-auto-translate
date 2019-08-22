<?php

namespace Ben182\AutoTranslate\AutoTranslators;

use Ben182\AutoTranslate\AutoTranslate;
use Ben182\AutoTranslate\Translators\TranslatorInterface;

class JsonTranslator extends AutoTranslate
{
    public function getTranslations(string $lang)
    {
        try {
            return json_decode(
                file_get_contents(
                    base_path("resources/lang/$lang.json")
                ),
                true
            );
        } catch (\ErrorException $e) {
            return [];
        }
    }

    public function getMissingTranslations(string $lang)
    {
        $sourceTranslations = $this->getSourceTranslations();
        $targetTranslations = $this->getTranslations($lang);

        $diff = array_diff(array_keys($sourceTranslations), array_keys($targetTranslations));

        return collect($sourceTranslations)->only($diff)->toArray();
    }

    public function fillLanguageFiles(string $language, array $translations)
    {
        $finalTranslations = array_merge(
            $this->getTranslations($language),
            $translations
        );

        file_put_contents(
            base_path("resources/lang/$language.json"),
            json_encode($finalTranslations, JSON_PRETTY_PRINT)
        );
    }

    public function getSourceTranslations()
    {
        return $this->getTranslations(config('auto-translate.source_language'));
    }
}
