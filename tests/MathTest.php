<?php

use PHPUnit\Framework\TestCase;

class MathTest extends TestCase {
    
    public function testDouble(){
        $this->assertEquals(4, \Syl\BrecoCcp2cp8\Math::double(2) );
    }
}