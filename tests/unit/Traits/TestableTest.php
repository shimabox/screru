<?php

namespace SMB\Screru\Tests\Traits;

use PHPUnit\Framework\TestCase;
use SMB\Screru\Tests\Wrapper\RemoteWebDriverTest;

/** @group Traits */
class TestableTest extends TestCase
{
    public function testTraitConstructorPreservesPhpunitTestName(): void
    {
        $name = 'it_can_be_confirmed_that_it_is_quit';
        $test = new RemoteWebDriverTest($name);
        $this->assertSame($name, $test->getName());
    }
}
