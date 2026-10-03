<?php

namespace SMB\Screru\Tests\Traits;

use PHPUnit\Framework\TestCase;
use PHPUnit\Runner\BaseTestRunner;
use SMB\Screru\Traits\Testable;
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

    public function testCaptureDecisionUsesPhpunitFailureStatus(): void
    {
        $test = new TestableFixture();
        $this->assertTrue($test->shouldCapture(true, BaseTestRunner::STATUS_FAILURE));
        $this->assertFalse($test->shouldCapture(false, BaseTestRunner::STATUS_FAILURE));
        $this->assertFalse($test->shouldCapture(true, BaseTestRunner::STATUS_PASSED));
    }
}

class TestableFixture extends TestCase
{
    use Testable;

    private $status = BaseTestRunner::STATUS_PASSED;

    public function getStatus(): int
    {
        return $this->status;
    }

    public function shouldCapture(bool $enabled, int $status): bool
    {
        $this->status = $status;
        if ($enabled) {
            $this->enableCaptureWhenAssertionFails();
        } else {
            $this->disableCaptureWhenAssertionFails();
        }
        return $this->takeCaptureWhenAssertionFails();
    }
}
