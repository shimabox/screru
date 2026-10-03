<?php

namespace SMB\Screru\Tests\Functional;

/**
 * Base
 */
abstract class Base extends \PHPUnit\Framework\TestCase
{
    // alias for function...
    use \SMB\Screru\Traits\Testable {
        setUp as protected traitSetUp;
        tearDown as protected traitTearDown;
    }

    /**
     * setUp
     */
    protected function setUp(): void
    {
        $this->traitSetUp();
    }

    /**
     * tearDown
     */
    protected function tearDown(): void
    {
        $this->traitTearDown();
    }
}
