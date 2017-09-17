<?php

namespace SMB\Screru\Tests\Elements;

use SMB\Screru\Elements\Spec;

/**
 * Test of SMB\Screru\Elements\Spec
 * 
 * @group Elements
 */
class SpecTest extends \PHPUnit_Framework_TestCase
{
    /**
     * デフォルト値を取得できる
     * @test
     */
    public function it_can_get_the_default_value()
    {
        $expectedSelector = '#id';

        $spec = new Spec($expectedSelector);

        $this->assertSame($expectedSelector, $spec->getSelector());
        $this->assertSame(Spec::EQUAL, $spec->getCondition());
        $this->assertSame(1, $spec->getExpectedElementCount());
    }

    /**
     * セットした値を取得できる
     * @test
     */
    public function it_can_get_the_set_value()
    {
        $expectedSelector = '#id';
        $expectedElementCount = 10;

        $spec = new Spec($expectedSelector, Spec::GREATER_THAN_OR_EQUAL, $expectedElementCount);

        $this->assertSame($expectedSelector, $spec->getSelector());
        $this->assertSame(Spec::GREATER_THAN_OR_EQUAL, $spec->getCondition());
        $this->assertSame($expectedElementCount, $spec->getExpectedElementCount());
    }
}
