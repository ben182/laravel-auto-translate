<?php

namespace Ben182\AutoTranslate\Exceptions;

use Exception;

class LanguageCodeNotExist extends Exception
{
    public static function throw(string $source, string $target): self
    {
        return new static('The language code "'.$source.'" or "'.$target.'" does not exist.');
    }
}
