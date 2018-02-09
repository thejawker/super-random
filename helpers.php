<?php

use TheJawker\SuperRandom\SuperRandom;

if (!function_exists('superRandom')) {
    function superRandom($settings = [])
    {
        return SuperRandom::generate($settings);
    }
}