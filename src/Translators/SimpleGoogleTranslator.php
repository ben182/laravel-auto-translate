<?php

namespace Ben182\AutoTranslate\Translators;

use Stichoza\GoogleTranslate\GoogleTranslate;

class SimpleGoogleTranslator implements TranslatorInterface {

    protected $translator;

    public function __construct() {
        $this->translator = new GoogleTranslate;
    }

    public function setSource(string $source) {
        $this->translator->setSource($source);
        return $this;
    }

    public function setTarget(string $target) {
        $this->translator->setTarget($target);
        return $this;
    }

    public function translate(string $string) : string {
        return $this->translator->translate($string);
    }
}
