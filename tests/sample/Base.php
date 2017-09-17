<?php

namespace SMB\Screru\Tests\Sample;

/**
 * Base
 */
abstract class Base extends \PHPUnit_Framework_TestCase
{
    // alias for function...
    use \SMB\Screru\Traits\Testable {
        setUp as protected traitSetUp;
        tearDown as protected traitTearDown;
    }

    /**
     * setUp
     */
    protected function setUp()
    {
        $this->traitSetUp();
    }

    /**
     * tearDown
     */
    protected function tearDown()
    {
        $this->traitTearDown();
    }
}
