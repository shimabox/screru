<?php

namespace SMB\Screru\Tests\Manual\Wrapper;

use SMB\Screru\Wrapper\RemoteWebDriver;

/**
 * Test of SMB\Screru\Wrapper\RemoteWebDriver
 * 
 * @group Wrapper
 */
class RemoteWebDriverTest extends \PHPUnit_Framework_TestCase
{
    use \SMB\Screru\Traits\Testable {
        setUp as protected traitSetUp;
        tearDown as protected traitTearDown;
    }

    /**
     * RemoteWebDriverが quit() されていることが確認できる
     * @test
     */
    public function it_can_be_confirmed_that_it_is_quit()
    {
        $driver = RemoteWebDriver::create($this->seleniumServerUrl, $this->createCapabilities()->get());

        $this->assertFalse($driver->isQuit());

        $driver->quit();

        $this->assertTrue($driver->isQuit());
    }

    /**
     * RemoteWebDriverが close() されていることが確認できる
     * @test
     */
    public function it_can_be_confirmed_that_it_is_closed()
    {
        $driver = RemoteWebDriver::create($this->seleniumServerUrl, $this->createCapabilities()->get());

        $this->assertFalse($driver->isClosed());

        $driver->close();

        $this->assertTrue($driver->isClosed());
    }
}
