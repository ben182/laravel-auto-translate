<?php

namespace Ben182\AutoTranslate\Translators;

interface TranslatorInterface
{
    public function setSource(string $source);

    public function setTarget(string $target);

    public function translate(string $string) : string;
}
