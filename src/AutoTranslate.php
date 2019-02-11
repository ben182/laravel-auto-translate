<?php

namespace Ben182\AutoTranslate;

use Illuminate\Support\Arr;
use Themsaid\Langman\Manager as Langman;
use Illuminate\Support\Facades\Cache;

class AutoTranslate
{
    protected $manager;
    protected $translator;

    protected $languageFiles;

    public function __construct(Langman $manager, $translator)
    {
        $this->manager = $manager;
        $this->translator = $translator;
        $this->translator->setSource(config('auto-translate.source_language'));

        $this->languageFiles = $this->manager->files();
    }

    // protected function getLanguageFile($language, $key) {
    //     return $this->languageFiles[$key][$language];
    // }

    public function getSourceTranslations() {
        $aReturn = [];

        foreach ($this->languageFiles as $fileKeyName => $languagesFile) {

            $allTranslations = $this->manager->getFileContent($languagesFile[config('auto-translate.source_language')]);

            $aReturn[$fileKeyName] = $allTranslations;
        }

        return $aReturn;
    }

    public function translateSourceTranslations($targetLanguage) {

        return Cache::remember('translateSourceTranslations' . $targetLanguage, 1440, function() use ($targetLanguage) {

            $this->translator->setTarget($targetLanguage);

            $source = $this->getSourceTranslations();

            $dottedSource = Arr::dot($source);

            foreach ($dottedSource as $key => $value) {
                $dottedSource[$key] = is_string($value) ? $this->translator->translate($value) : $value;
            }

            return $this->array_undot($dottedSource);
        });
    }

    // public function getAllLanguage($lang) {

    //     $langParams = Arr::wrap($lang);

    //     dump($this->languageFiles);

    //     $aReturn = [];

    //     foreach ($langParams as $lang) {
    //         foreach ($this->languageFiles as $fileKeyName => $languagesFile) {

    //             $allTranslations = $this->manager->getFileContent($languagesFile[$lang]);

    //             $aReturn[$fileKeyName][$lang] = $allTranslations;
    //         }
    //     }

    //     return $aReturn;
    // }

    public function fillLanguageFiles($language, $data) {
        foreach ($data as $languageFileKey => $translations) {


            $translations = array_map(function($item) use($language) {
                return [
                    $language => $item,
                ];
            }, $translations);



            $this->manager->fillKeys($languageFileKey, $translations);
        }
    }

    protected function array_undot($dottedArray, $initialArray = [])
    {
        foreach ($dottedArray as $key => $value) {
            array_set($initialArray, $key, $value);
        }
        return $initialArray;
    }
}
