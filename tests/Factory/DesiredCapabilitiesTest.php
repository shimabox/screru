<?php

namespace SMB\Screru\Tests\Factory;

use SMB\Screru\Factory\DesiredCapabilities;

use Facebook\WebDriver\Remote\WebDriverBrowserType;

/**
 * Test of SMB\Screru\Factory\DesiredCapabilities
 * 
 * @group Factory
 */
class DesiredCapabilitiesTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Chrome用のオブジェクトを作成出来る
     * @test
     * @group chrome
     */
    public function it_can_create_an_object_for_chrome()
    {
        if (getenv('ENABLED_CHROME_DRIVER') !== 'true') {
            $this->assertTrue(true);
            return;
        }

        $cap = new DesiredCapabilities(WebDriverBrowserType::CHROME);

        $actual = $cap->get();

        $this->assertInstanceOf('Facebook\WebDriver\Remote\DesiredCapabilities', $actual);
        $this->assertSame(WebDriverBrowserType::CHROME, $actual->getBrowserName());
    }

    /**
     * Firefox用のオブジェクトを作成出来る
     * @test
     * @group firefox
     */
    public function it_can_create_an_object_for_firefox()
    {
        if (getenv('ENABLED_FIREFOX_DRIVER') !== 'true') {
            $this->assertTrue(true);
            return;
        }
 
        $cap = new DesiredCapabilities(WebDriverBrowserType::FIREFOX);

        $actual = $cap->get();

        $this->assertInstanceOf('Facebook\WebDriver\Remote\DesiredCapabilities', $actual);
        $this->assertSame(WebDriverBrowserType::FIREFOX, $actual->getBrowserName());
    }

    /**
     * IE用のオブジェクトを作成出来る
     * @test
     * @group ie
     */
    public function it_can_create_an_object_for_ie()
    {
        if (
            getenv('ENABLED_IE_DRIVER') !== 'true'
            || getenv('IS_PLATFORM_WINDOWS') !== 'true'
        ) {
            $this->assertTrue(true);
            return;
        }

        $cap = new DesiredCapabilities(WebDriverBrowserType::IE);

        $actual = $cap->get();

        $this->assertInstanceOf('Facebook\WebDriver\Remote\DesiredCapabilities', $actual);
        $this->assertSame(WebDriverBrowserType::IE, $actual->getBrowserName());
    }

    /**
     * ブラウザにchromeを指定したときにchrome無効としていた場合DisabledWebDriverExceptionがthrowされる
     * @test
     * @runInSeparateProcess
     * @expectedException        SMB\Screru\Exception\DisabledWebDriverException
     * @expectedExceptionMessage Disabled chrome webdriver
     */
    public function it_throws_DisabledWebDriverException_when_disable_chrome_when_specifying_chrome_for_browser()
    {
        putenv('ENABLED_CHROME_DRIVER=');
        new DesiredCapabilities(WebDriverBrowserType::CHROME);
    }

    /**
     * プラットフォームがwindowsのときにchrome driverのパスが指定されていない場合NotExistsWebDriverExceptionがthrowされる
     * @test
     * @runInSeparateProcess
     * @expectedException        SMB\Screru\Exception\NotExistsWebDriverException
     * @expectedExceptionMessage not exists chrome webdriver
     */
    public function it_throws_NotExistsWebDriverException_when_the_platform_is_windows_the_chrome_driver_path_is_unspecified()
    {
        putenv('ENABLED_CHROME_DRIVER=true');
        putenv('IS_PLATFORM_WINDOWS=true');
        putenv('CHROME_DRIVER_PATH=');
        new DesiredCapabilities(WebDriverBrowserType::CHROME);
    }

    /**
     * ブラウザにfirefoxを指定したときにfirefox無効としていた場合DisabledWebDriverExceptionがthrowされる
     * @test
     * @runInSeparateProcess
     * @expectedException        SMB\Screru\Exception\DisabledWebDriverException
     * @expectedExceptionMessage Disabled firefox webdriver
     */
    public function it_throws_DisabledWebDriverException_when_disable_firefox_when_specifying_firefox_for_browser()
    {
        putenv('ENABLED_FIREFOX_DRIVER=');
        new DesiredCapabilities(WebDriverBrowserType::FIREFOX);
    }

    /**
     * プラットフォームがwindowsのときにfirefox(gecko) driverのパスが指定されていない場合NotExistsWebDriverExceptionがthrowされる
     * @test
     * @runInSeparateProcess
     * @expectedException        SMB\Screru\Exception\NotExistsWebDriverException
     * @expectedExceptionMessage not exists firefox webdriver
     */
    public function it_throws_NotExistsWebDriverException_when_the_platform_is_windows_the_firefox_driver_path_is_unspecified()
    {
        putenv('ENABLED_FIREFOX_DRIVER=true');
        putenv('IS_PLATFORM_WINDOWS=true');
        putenv('FIREFOX_DRIVER_PATH=');
        new DesiredCapabilities(WebDriverBrowserType::FIREFOX);
    }

    /**
     * ブラウザにfirefoxを指定したときにfirefox無効としていた場合DisabledWebDriverExceptionがthrowされる
     * @test
     * @runInSeparateProcess
     * @expectedException        SMB\Screru\Exception\DisabledWebDriverException
     * @expectedExceptionMessage Disabled ie webdriver
     */
    public function it_throws_DisabledWebDriverException_when_disable_ie_when_specifying_ie_for_browser()
    {
        putenv('ENABLED_IE_DRIVER=');
        new DesiredCapabilities(WebDriverBrowserType::IE);
    }

    /**
     * プラットフォームがwindowsのときにie driverのパスが指定されていない場合NotExistsWebDriverExceptionがthrowされる
     * @test
     * @runInSeparateProcess
     * @expectedException        SMB\Screru\Exception\NotExistsWebDriverException
     * @expectedExceptionMessage not exists ie webdriver
     */
    public function it_throws_NotExistsWebDriverException_when_the_platform_is_windows_the_ie_driver_path_is_unspecified()
    {
        putenv('ENABLED_IE_DRIVER=true');
        putenv('IS_PLATFORM_WINDOWS=true');
        putenv('IE_DRIVER_PATH=');
        new DesiredCapabilities(WebDriverBrowserType::IE);
    }
}
