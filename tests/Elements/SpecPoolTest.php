<?php

namespace SMB\Screru\Tests\Elements;

use SMB\Screru\Elements\Spec;
use SMB\Screru\Elements\SpecPool;

/**
 * Test of SMB\Screru\Elements\SpecPool
 * 
 * @group Elements
 */
class SpecPoolTest extends \PHPUnit_Framework_TestCase
{
    /**
     * 設定したSpecを取得できる
     * @test
     */
    public function it_can_get_the_specified_Spec()
    {
        $specPool = (new SpecPool())
                        ->addSpec(new Spec('#id'))
                        ->addSpec(new Spec('.class'));

        $specs = $specPool->getSpec();

        $this->assertCount(2, $specs);
        foreach ($specs as $spec) {
            $this->assertInstanceOf('SMB\Screru\Elements\Spec', $spec);
        }
    }

    /**
     * 設定したSpecをクリアできる
     * @test
     */
    public function it_can_clear_the_specified_Spec()
    {
        $specPool = (new SpecPool())
                        ->addSpec(new Spec('#id'))
                        ->addSpec(new Spec('.class'));

        $specs = $specPool->getSpec();
        $this->assertCount(2, $specs);

        // clear
        $specPool->clearSpec();

        $clearedSpecs = $specPool->getSpec();
        $this->assertCount(0, $clearedSpecs);
    }
}
