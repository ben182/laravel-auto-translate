<?php

namespace Ben182\LaravelAutoTranslate;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Ben182\LaravelAutoTranslate\Skeleton\SkeletonClass
 */
class LaravelAutoTranslateFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laravel-auto-translate';
    }
}
