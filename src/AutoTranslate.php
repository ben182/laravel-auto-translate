<?php

namespace Ben182\AutoTranslate;

use Illuminate\Support\Arr;
use Themsaid\Langman\Manager as Langman;
use Ben182\AutoTranslate\Translators\TranslatorInterface;

class AutoTranslate
{
    protected $manager;
    protected $translator;

    public function __construct(Langman $manager, TranslatorInterface $translator)
    {
        $this->manager = $manager;
        $this->translator = $translator;
        $this->translator->setSource(config('auto-translate.source_language'));
    }

    public function getSourceTranslations()
    {
        return $this->getTranslations(config('auto-translate.source_language'));
    }

    public function getTranslations(string $lang)
    {
        $aReturn = [];

        $files = $this->manager->files();

        foreach ($files as $fileKeyName => $languagesFile) {
            if (! isset($languagesFile[$lang])) {
                continue;
            }

            $allTranslations = $this->manager->getFileContent($languagesFile[$lang]);

            $aReturn[$fileKeyName] = $allTranslations;
        }

        return $aReturn;
    }

    public function getMissingTranslations(string $lang)
    {
        $source = $this->getSourceTranslations();
        $lang = $this->getTranslations($lang);

        $dottedSource = Arr::dot($source);
        $dottedlang = Arr::dot($lang);

        $diff = array_diff(array_keys($dottedSource), array_keys($dottedlang));

        return collect($dottedSource)->only($diff);
    }

    public function translate(string $targetLanguage, $data)
    {
        $this->translator->setTarget($targetLanguage);

        $dottedSource = Arr::dot($data);

        foreach ($dottedSource as $key => $value) {
            $dottedSource[$key] = is_string($value) ? $this->translator->translate($value) : $value;
        }

        return $this->array_undot($dottedSource);
    }

    public function fillLanguageFiles(string $language, array $data)
    {
        foreach ($data as $languageFileKey => $translations) {
            $translations = array_map(function ($item) use ($language) {
                return [
                    $language => $item,
                ];
            }, $translations);

            $this->manager->fillKeys($languageFileKey, $translations);
        }
    }

    public function array_undot(array $dottedArray, array $initialArray = []) : array
    {
        foreach ($dottedArray as $key => $value) {
            Arr::set($initialArray, $key, $value);
        }

        return $initialArray;
    }
}
