<?php

namespace TheJawker\SuperRandom;

use Illuminate\Support\ServiceProvider;

class SuperRandomServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('super-random', function () {
            return $this->app->make(SuperRandomCodeGenerator::class);
        });
    }
}