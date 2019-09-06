<?php

namespace Ben182\AutoTranslate\Translators;

use Google\Cloud\Translate\TranslateClient;

class GoogleCloudTranslator implements TranslatorInterface
{
    protected $translator;
    protected $source;
    protected $target;

    public function __construct()
    {
        $this->translator = new TranslateClient([
            'key' => config('auto-translate.google_cloud_translator.api_key'),
        ]);
    }

    public function setSource(string $source)
    {
        $this->source = $source;

        return $this;
    }

    public function setTarget(string $target)
    {
        $this->target = $target;

        return $this;
    }

    public function translate(string $string) : string
    {
        $result = $this->translator->translate($string, [
            'source' => $this->source,
            'target' => $this->target,
        ]);

        return $result['text'];
    }
}
