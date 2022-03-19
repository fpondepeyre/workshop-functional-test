<?php

namespace App\Tests\Util;

use App\Util\Calculator;
use PHPUnit\Framework\TestCase;

class CalculatorTest extends TestCase
{
    public function testSomething(): void
    {
        $calculator = new Calculator();
        $this->assertSame(42, $calculator->add(20, 22));
    }
}
