<?php

namespace TheJawker\SuperRandom\Test;

use TheJawker\SuperRandom\SuperRandom;
use TheJawker\SuperRandom\SuperRandomCodeGenerator;

class FacadeWorksTest extends TestCase
{
    /** @test */
    public function facade_works()
    {
        $this->assertInstanceOf(SuperRandomCodeGenerator::class, SuperRandom::setConfig([]));
    }
}