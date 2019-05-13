<?php

namespace Ben182\AutoTranslate\Translators;

use Scn\DeeplApiConnector\DeeplClient;
use Scn\DeeplApiConnector\Model\TranslationConfig;
use Scn\DeeplApiConnector\Exception\RequestException;
use Ben182\AutoTranslate\Exceptions\LanguageCodeNotExist;

class DeeplTranslator implements TranslatorInterface
{
    protected $translator;
    protected $source;
    protected $target;

    public function __construct()
    {
        $this->translator = DeeplClient::create(config('auto-translate.deepl.api_key'));
    }

    public function setSource(string $source)
    {
        $this->source = strtoupper($source);

        return $this;
    }

    public function setTarget(string $target)
    {
        $this->target = strtoupper($target);

        return $this;
    }

    public function translate(string $string) : string
    {
        $translation = new TranslationConfig(
            $string,
            $this->target,
            $this->source
        );

        try {
            return $this->translator->getTranslation($translation)->getText();
        } catch (RequestException $th) {
            if ($th->getMessage() === '400 {"message":"Value for \'target_lang\' is not supported."}') {
                throw LanguageCodeNotExist::throw($this->source, $this->target);
            }

            throw $th;
        }
    }
}
