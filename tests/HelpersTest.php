<?php

namespace TheJawker\SuperRandom\Test;

class HelpersTest extends TestCase
{
    /** @test */
    public function a_helper_function_can_be_used()
    {
        $code = superRandom([
            'length' => 12
        ]);

        $this->assertEquals(12, strlen($code));
    }
}