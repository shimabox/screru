<?php

namespace SMB\Screru\Tests;

use SMB\Screru\Tests\Sample\Base;
use SMB\UrlStatus;

use Facebook\WebDriver\WebDriverExpectedCondition;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\Remote\WebDriverBrowserType;

/**
 * Test of access
 *
 * @group functional
 */
class AccessTest extends Base
{
    /**
     * setUp
     */
    protected function setUp()
    {
        parent::setUp();
    }

    /**
     * tearDown
     */
    protected function tearDown()
    {
        parent::tearDown();
    }

    /**
     * PC:Chrome Testページにアクセスできる
     * @test
     * @group chrome
     */
    public function it_can_access_to_testpage_of_pc_chrome()
    {
        $cap = $this->createCapabilities(WebDriverBrowserType::CHROME);

        $driver = $this->createDriver($cap);
        $driver->get('http://localhost:8000');

        $driver->wait()->until(
            WebDriverExpectedCondition::visibilityOfElementLocated(
                WebDriverBy::cssSelector('#pc')
            )
        );
        $driver->wait()->until(
            WebDriverExpectedCondition::invisibilityOfElementLocated(
                WebDriverBy::cssSelector('#sp')
            )
        );

        $this->assertEquals('test page', $driver->getTitle());

        $urlStatus = UrlStatus::get($driver->getCurrentURL());
        $this->assertTrue($urlStatus->is200());
    }

    /**
     * SP:Chrome Testページにアクセスできる
     * @test
     * @group chrome
     */
    public function it_can_access_to_testpage_of_sp_chrome()
    {
        $cap = $this->createCapabilities(WebDriverBrowserType::CHROME);
        $cap->settingDefaultUserAgent();

        $driver = $this->createDriver($cap);
        $driver->get('http://localhost:8000');

        $driver->wait()->until(
            WebDriverExpectedCondition::visibilityOfElementLocated(
                WebDriverBy::cssSelector('#sp')
            )
        );
        $driver->wait()->until(
            WebDriverExpectedCondition::invisibilityOfElementLocated(
                WebDriverBy::cssSelector('#pc')
            )
        );

        $this->assertEquals('test page', $driver->getTitle());

        $urlStatus = UrlStatus::get($driver->getCurrentURL());
        $this->assertTrue($urlStatus->is200());
    }

    /**
     * PC:Firefox Testページにアクセスできる
     * @test
     * @group firefox
     */
    public function it_can_access_to_testpage_of_pc_firefox()
    {
        $cap = $this->createCapabilities(WebDriverBrowserType::FIREFOX);

        $driver = $this->createDriver($cap);
        $driver->get('http://localhost:8000');

        $driver->wait()->until(
            WebDriverExpectedCondition::visibilityOfElementLocated(
                WebDriverBy::cssSelector('#pc')
            )
        );
        $driver->wait()->until(
            WebDriverExpectedCondition::invisibilityOfElementLocated(
                WebDriverBy::cssSelector('#sp')
            )
        );

        $this->assertEquals('test page', $driver->getTitle());

        $urlStatus = UrlStatus::get($driver->getCurrentURL());
        $this->assertTrue($urlStatus->is200());
    }

    /**
     * SP:Firefox Testページにアクセスできる
     * @test
     * @group firefox
     */
    public function it_can_access_to_testpage_of_sp_firefox()
    {
        $cap = $this->createCapabilities(WebDriverBrowserType::FIREFOX);
        $cap->settingDefaultUserAgent();

        $driver = $this->createDriver($cap);
        $driver->get('http://localhost:8000');

        $driver->wait()->until(
            WebDriverExpectedCondition::visibilityOfElementLocated(
                WebDriverBy::cssSelector('#sp')
            )
        );
        $driver->wait()->until(
            WebDriverExpectedCondition::invisibilityOfElementLocated(
                WebDriverBy::cssSelector('#pc')
            )
        );

        $this->assertEquals('test page', $driver->getTitle());

        $urlStatus = UrlStatus::get($driver->getCurrentURL());
        $this->assertTrue($urlStatus->is200());
    }

    /**
     * PC:IE Testページにアクセスできる
     * @test
     * @group ie
     */
    public function it_can_access_to_testpage_of_pc_ie()
    {
        $cap = $this->createCapabilities(WebDriverBrowserType::IE);

        $driver = $this->createDriver($cap);
        $driver->get('http://localhost:8000');

        $driver->wait()->until(
            WebDriverExpectedCondition::visibilityOfElementLocated(
                WebDriverBy::cssSelector('#pc')
            )
        );
        $driver->wait()->until(
            WebDriverExpectedCondition::invisibilityOfElementLocated(
                WebDriverBy::cssSelector('#sp')
            )
        );

        $this->assertEquals('test page', $driver->getTitle());

        $urlStatus = UrlStatus::get($driver->getCurrentURL());
        $this->assertTrue($urlStatus->is200());
    }

    /**
     * SP:IE Testページにアクセスできる
     * @test
     * @group ie
     */
    public function it_can_access_to_testpage_of_sp_ie()
    {
        $cap = $this->createCapabilities(WebDriverBrowserType::IE);
        $cap->settingDefaultUserAgent();

        $driver = $this->createDriver($cap);
        $driver->get('http://localhost:8000');

        $driver->wait()->until(
            WebDriverExpectedCondition::visibilityOfElementLocated(
                WebDriverBy::cssSelector('#sp')
            )
        );
        $driver->wait()->until(
            WebDriverExpectedCondition::invisibilityOfElementLocated(
                WebDriverBy::cssSelector('#pc')
            )
        );

        $this->assertEquals('test page', $driver->getTitle());

        $urlStatus = UrlStatus::get($driver->getCurrentURL());
        $this->assertTrue($urlStatus->is200());
    }

}
