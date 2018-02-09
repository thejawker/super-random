<?php

namespace TheJawker\SuperRandom;

use Illuminate\Support\Facades\Facade;

class SuperRandom extends Facade
{
    /**
     * Get the binding in the IoC container
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'super-random';
    }
}