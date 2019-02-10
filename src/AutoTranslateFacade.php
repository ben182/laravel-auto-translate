<?php

namespace Ben182\AutoTranslate;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Ben182\AutoTranslate\Skeleton\SkeletonClass
 */
class AutoTranslateFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'auto-translate';
    }
}
