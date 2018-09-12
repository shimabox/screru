<?php

namespace SMB\Screru\Tests\Sample;

use SMB\UrlStatus;

use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Facebook\WebDriver\Remote\WebDriverBrowserType;
use Facebook\WebDriver\Exception\TimeOutException;

/**
 * Test of sample
 *
 * @group Sample
 */
class SampleTest extends Base
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
     * PC:Chrome Googleトップページにアクセスできる
     * @test
     * @group chrome
     */
    public function it_can_access_to_google_of_pc_chrome()
    {
        $cap = $this->createCapabilities(WebDriverBrowserType::CHROME);

        $driver = $this->createDriver($cap);
        $driver->get('https://www.google.com/webhp?gl=us&hl=en&gws_rd=cr');

        $this->assertEquals('Google', $driver->getTitle());

        $urlStatus = UrlStatus::get($driver->getCurrentURL());
        $this->assertTrue($urlStatus->is200());
    }

    /**
     * SP(UA偽装):Chrome Googleトップページにアクセスできる
     * @test
     * @group chrome
     */
    public function it_can_access_to_google_of_forge_ua_sp_chrome()
    {
        $cap = $this->createCapabilities(WebDriverBrowserType::CHROME);
        // Android 7.1.1
        $cap->setUserAgent('Mozilla/5.0 (Linux; Android 7.1.1; Nexus 5X Build/N4F26I) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.91 Mobile Safari/537.36');

        $driver = $this->createDriver($cap);
        $driver->get('https://www.google.com/webhp?gl=us&hl=en&gws_rd=cr');

        // metaにviewportがあること
        $elem = $driver->findElement(WebDriverBy::cssSelector("meta[name='viewport']"));

        $this->assertInstanceOf('Facebook\WebDriver\Remote\RemoteWebElement', $elem);

        $urlStatus = UrlStatus::get($driver->getCurrentURL());
        $this->assertTrue($urlStatus->is200());
    }

    /**
     * PC:Firefox Googleトップページにアクセスできる
     * @test
     * @group firefox
     */
    public function it_can_access_to_google_of_pc_firefox()
    {
        $cap = $this->createCapabilities(WebDriverBrowserType::FIREFOX);

        $driver = $this->createDriver($cap);
        $driver->get('https://www.google.com/webhp?gl=us&hl=en&gws_rd=cr');

        $this->assertEquals('Google', $driver->getTitle());

        $urlStatus = UrlStatus::get($driver->getCurrentURL());
        $this->assertTrue($urlStatus->is200());
    }

    /**
     * SP(UA偽装):Firefox Googleトップページにアクセスできる
     * @test
     * @group firefox
     */
    public function it_can_access_to_google_of_forge_ua_sp_firefox()
    {
        $cap = $this->createCapabilities(WebDriverBrowserType::FIREFOX);
        // iOS 10.3.2
        $cap->settingDefaultUserAgent();

        $driver = $this->createDriver($cap);
        $driver->get('https://www.google.com/webhp?gl=us&hl=en&gws_rd=cr');

        // metaにviewportがあること
        $elem = $driver->findElement(WebDriverBy::cssSelector("meta[name='viewport']"));

        $this->assertInstanceOf('Facebook\WebDriver\Remote\RemoteWebElement', $elem);

        $urlStatus = UrlStatus::get($driver->getCurrentURL());
        $this->assertTrue($urlStatus->is200());
    }

    /**
     * PC:IE Googleトップページにアクセスできる
     * @test
     * @group ie
     */
    public function it_can_access_to_google_of_pc_ie()
    {
        $cap = $this->createCapabilities(WebDriverBrowserType::IE);

        $driver = $this->createDriver($cap);
        $driver->get('https://www.google.com/webhp?gl=us&hl=en&gws_rd=cr');

        $this->assertEquals('Google', $driver->getTitle());

        $urlStatus = UrlStatus::get($driver->getCurrentURL());
        $this->assertTrue($urlStatus->is200());
    }

    /**
     * Chrome 画面のサイズが変更できる
     * @test
     * @group chrome
     */
    public function it_can_change_the_screen_size_of_chrome()
    {
        $cap = $this->createCapabilities(WebDriverBrowserType::CHROME);

        $driver = $this->createDriver($cap);
        $driver->get('https://developers.google.com');

        try {
            // 画面サイズを一旦MAXに
            $this->windowMaximize($driver);

            $driver->wait()->until(
                WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::cssSelector('div.devsite-header-upper-tabs > nav'))
            );
            $driver->wait()->until(
                WebDriverExpectedCondition::invisibilityOfElementLocated(WebDriverBy::cssSelector('button.devsite-expand-section-nav'))
            );

            $this->assertTrue(true);

        } catch (TimeOutException $e) {
            $this->fail($e->getMessage());
        }

        try {
            // 画面サイズを 414x736(iPhone6 Plus) に変更
            $dimension = $this->createDimension(['w' => 414, 'h' => 736]);
            $this->windowSetSize($driver, $dimension);

            $driver->wait()->until(
                WebDriverExpectedCondition::invisibilityOfElementLocated(WebDriverBy::cssSelector('div.devsite-header-upper-tabs > nav'))
            );
            $driver->wait()->until(
                WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::cssSelector('button.devsite-expand-section-nav'))
            );

            $this->assertTrue(true);

        } catch (TimeOutException $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * Firefox 画面のサイズが変更できる
     * @test
     * @group firefox
     */
    public function it_can_change_the_screen_size_of_firefox()
    {
        $cap = $this->createCapabilities(WebDriverBrowserType::FIREFOX);

        $driver = $this->createDriver($cap);
        $driver->get('https://developers.google.com');

        try {
            // 画面サイズを一旦MAXに
            $this->windowMaximize($driver);

            $driver->wait()->until(
                WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::cssSelector('div.devsite-header-upper-tabs > nav'))
            );
            $driver->wait()->until(
                WebDriverExpectedCondition::invisibilityOfElementLocated(WebDriverBy::cssSelector('button.devsite-expand-section-nav'))
            );

            $this->assertTrue(true);

        } catch (TimeOutException $e) {
            $this->fail($e->getMessage());
        }

        try {
            // 画面サイズを 414x736(iPhone6 Plus) に変更
            $dimension = $this->createDimension(['w' => 414, 'h' => 736]);
            $this->windowSetSize($driver, $dimension);

            $driver->wait()->until(
                WebDriverExpectedCondition::invisibilityOfElementLocated(WebDriverBy::cssSelector('div.devsite-header-upper-tabs > nav'))
            );
            $driver->wait()->until(
                WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::cssSelector('button.devsite-expand-section-nav'))
            );

            $this->assertTrue(true);

        } catch (TimeOutException $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * IE 画面のサイズが変更できる
     * @test
     * @group ie
     */
    public function it_can_change_the_screen_size_of_ie()
    {
        $cap = $this->createCapabilities(WebDriverBrowserType::IE);

        $driver = $this->createDriver($cap);
        $driver->get('https://developers.google.com');

        try {
            // 画面サイズを一旦MAXに
            $this->windowMaximize($driver);

            $driver->wait()->until(
                WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::cssSelector('div.devsite-header-upper-tabs > nav'))
            );
            $driver->wait()->until(
                WebDriverExpectedCondition::invisibilityOfElementLocated(WebDriverBy::cssSelector('button.devsite-expand-section-nav'))
            );

            $this->assertTrue(true);

        } catch (TimeOutException $e) {
            $this->fail($e->getMessage());
        }

        try {
            // 画面サイズを 414x736(iPhone6 Plus) に変更
            $dimension = $this->createDimension(['w' => 414, 'h' => 736]);
            $this->windowSetSize($driver, $dimension);

            $driver->wait()->until(
                WebDriverExpectedCondition::invisibilityOfElementLocated(WebDriverBy::cssSelector('div.devsite-header-upper-tabs > nav'))
            );
            $driver->wait()->until(
                WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::cssSelector('button.devsite-expand-section-nav'))
            );

            $this->assertTrue(true);

        } catch (TimeOutException $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * PC:Chrome 複数window(tab)を立ち上げ後にすべてのwindow(tab)が閉じられる
     * @test
     * @group chrome
     */
    public function it_can_all_windows_closed_of_pc_chrome()
    {
        $cap = $this->createCapabilities(WebDriverBrowserType::CHROME);

        $driver = $this->createDriver($cap);
        $driver->get('https://www.google.com/search?gl=us&hl=en&gws_rd=cr&q=hello');

        // new window(tab)
        $driver->executeScript("window.open()");
        $handles = $driver->getWindowHandles();
        $driver->switchTo()->window(end($handles));

        $driver->get('https://www.google.com/search?gl=us&hl=en&gws_rd=cr&q=helloworld');
    }

    /**
     * SP(UA偽装):Chrome 複数window(tab)を立ち上げ後にすべてのwindow(tab)が閉じられる
     * @test
     * @group chrome
     */
    public function it_can_all_windows_closed_of_forge_ua_sp_chrome()
    {
        $cap = $this->createCapabilities(WebDriverBrowserType::CHROME);
        $cap->settingDefaultUserAgent();
        $dimension = $this->createDimension(['w' => 375, 'h' => 667]);

        $driver = $this->createDriver($cap, $dimension);
        $driver->get('https://www.google.com/search?gl=us&hl=en&gws_rd=cr&q=hello');

        // new window(tab)
        $driver->executeScript("window.open()");
        $handles = $driver->getWindowHandles();
        $driver->switchTo()->window(end($handles));

        $driver->get('https://www.google.com/search?gl=us&hl=en&gws_rd=cr&q=helloworld');
    }

    /**
     * PC:Firefox 複数window(tab)を立ち上げ後にすべてのwindow(tab)が閉じられる
     * @test
     * @group firefox
     */
    public function it_can_all_windows_closed_of_pc_firefox()
    {
        $cap = $this->createCapabilities(WebDriverBrowserType::FIREFOX);

        $driver = $this->createDriver($cap);
        $driver->get('https://www.google.com/search?gl=us&hl=en&gws_rd=cr&q=hello');

        // new window(tab)
        $driver->executeScript("window.open()");
        $handles = $driver->getWindowHandles();
        $driver->switchTo()->window(end($handles));

        $driver->get('https://www.google.com/search?gl=us&hl=en&gws_rd=cr&q=helloworld');
    }

    /**
     * SP(UA偽装):Firefox 複数window(tab)を立ち上げ後にすべてのwindow(tab)が閉じられる
     * @test
     * @group firefox
     */
    public function it_can_all_windows_closed_of_forge_ua_sp_firefox()
    {
        $cap = $this->createCapabilities(WebDriverBrowserType::FIREFOX);
        $cap->settingDefaultUserAgent();
        $dimension = $this->createDimension(['w' => 375, 'h' => 667]);

        $driver = $this->createDriver($cap, $dimension);
        $driver->get('https://www.google.com/search?gl=us&hl=en&gws_rd=cr&q=hello');

        // new window(tab)
        $driver->executeScript("window.open()");
        $handles = $driver->getWindowHandles();
        $driver->switchTo()->window(end($handles));

        $driver->get('https://www.google.com/search?gl=us&hl=en&gws_rd=cr&q=helloworld');
    }

    /**
     * IE 複数window(tab)を立ち上げ後にすべてのwindow(tab)が閉じられる
     * @test
     * @group ie
     */
    public function it_can_all_windows_closed_of_ie()
    {
        $cap = $this->createCapabilities(WebDriverBrowserType::IE);

        $driver = $this->createDriver($cap);
        $driver->get('https://shimabox.net');

        $driver->wait()->until(
            WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::cssSelector('#app button.navbar-toggler'))
        );
        $driver->findElement(WebDriverBy::cssSelector('#app button.navbar-toggler'))->click();

        $driver->wait()->until(
            WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::cssSelector('#navbar-header i.fa-github + a'))
        );
        $driver->findElement(WebDriverBy::cssSelector('#navbar-header i.fa-github + a'))->click();

        sleep(1);
    }
}
