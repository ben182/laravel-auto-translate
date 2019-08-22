<?php

namespace Ben182\AutoTranslate\AutoTranslators;

use Illuminate\Support\Arr;
use Themsaid\Langman\Manager as Langman;
use Ben182\AutoTranslate\AutoTranslate;
use Ben182\AutoTranslate\Translators\TranslatorInterface;

class PhpTranslator extends AutoTranslate
{
    protected $manager;

    public function __construct(TranslatorInterface $translator, Langman $manager)
    {
        $this->manager = $manager;

        parent::__construct($translator);
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

        return collect($dottedSource)->only($diff)->toArray();
    }

    public function fillLanguageFiles(string $language, array $data)
    {
        $data = $this->undotArray($data);

        foreach ($data as $languageFileKey => $translations) {
            $translations = array_map(function ($item) use ($language) {
                return [$language => $item];
            }, $translations);

            $this->manager->fillKeys($languageFileKey, $translations);
        }
    }

    public function getSourceTranslations()
    {
        return Arr::dot(
            $this->getTranslations(
                config('auto-translate.source_language')
            )
        );
    }

    public function undotArray(array $dottedArray, array $initialArray = []) : array
    {
        foreach ($dottedArray as $key => $value) {
            Arr::set($initialArray, $key, $value);
        }

        return $initialArray;
    }
}
