<?php

namespace Ben182\AutoTranslate\Translators;

use Stichoza\GoogleTranslate\GoogleTranslate;
use Ben182\AutoTranslate\Exceptions\LanguageCodeNotExist;

class SimpleGoogleTranslator implements TranslatorInterface
{
    protected $translator;
    protected $source;
    protected $target;

    public function __construct()
    {
        $this->translator = new GoogleTranslate;

        if (config('auto-translate.simple_google_translator.proxy')) {
            $this->translator->setOptions([
                'proxy' => config('auto-translate.simple_google_translator.proxy'),
            ]);
        }
    }

    public function setSource(string $source)
    {
        $this->source = $source;

        $this->translator->setSource($source);

        return $this;
    }

    public function setTarget(string $target)
    {
        $this->target = $target;

        $this->translator->setTarget($target);

        return $this;
    }

    public function translate(string $string) : string
    {
        try {
            sleep(random_int(config('auto-translate.simple_google_translator.sleep_between_requests')[0], config('auto-translate.simple_google_translator.sleep_between_requests')[1]));

            return $this->translator->translate($string);
        } catch (\Throwable $th) {
            if ($th->getMessage() === 'Return value of Stichoza\GoogleTranslate\GoogleTranslate::translate() must be of the type string, null returned') {
                throw LanguageCodeNotExist::throw($this->source, $this->target);
            }

            throw $th;
        }
    }
}
