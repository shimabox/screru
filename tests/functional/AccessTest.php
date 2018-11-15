<?php

namespace SMB\Screru\Tests\Functional;

use SMB\UrlStatus;

use Facebook\WebDriver\WebDriverExpectedCondition;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\Remote\WebDriverBrowserType;

/**
 * Test of access
 *
 * @group Functional
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
        $driver->get('http://localhost:' . getenv('LOCAL_PORT'));

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
        $driver->get('http://localhost:' . getenv('LOCAL_PORT'));

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
        $driver->get('http://localhost:' . getenv('LOCAL_PORT'));

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
        $driver->get('http://localhost:' . getenv('LOCAL_PORT'));

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
        $driver->get('http://localhost:' . getenv('LOCAL_PORT'));

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
     * Chrome 画面のサイズが変更できる
     * @test
     * @group chrome
     */
    public function it_can_change_the_screen_size_of_chrome()
    {
        $cap = $this->createCapabilities(WebDriverBrowserType::CHROME);

        $driver = $this->createDriver($cap);
        $driver->get('http://localhost:' . getenv('LOCAL_PORT'));

        try {
            // 画面サイズを一旦MAXに
            $this->windowMaximize($driver);

            $driver->wait()->until(
                WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::cssSelector('nav div.navbar-collapse'))
            );
            $driver->wait()->until(
                WebDriverExpectedCondition::invisibilityOfElementLocated(WebDriverBy::cssSelector('nav span.navbar-toggler-icon'))
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
                WebDriverExpectedCondition::invisibilityOfElementLocated(WebDriverBy::cssSelector('nav div.navbar-collapse'))
            );
            $driver->wait()->until(
                WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::cssSelector('nav span.navbar-toggler-icon'))
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
        $driver->get('http://localhost:' . getenv('LOCAL_PORT'));

        try {
            // 画面サイズを一旦MAXに
            $this->windowMaximize($driver);

            $driver->wait()->until(
                WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::cssSelector('nav div.navbar-collapse'))
            );
            $driver->wait()->until(
                WebDriverExpectedCondition::invisibilityOfElementLocated(WebDriverBy::cssSelector('nav span.navbar-toggler-icon'))
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
                WebDriverExpectedCondition::invisibilityOfElementLocated(WebDriverBy::cssSelector('nav div.navbar-collapse'))
            );
            $driver->wait()->until(
                WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::cssSelector('nav span.navbar-toggler-icon'))
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
        $driver->get('http://localhost:' . getenv('LOCAL_PORT'));

        try {
            // 画面サイズを一旦MAXに
            $this->windowMaximize($driver);

            $driver->wait()->until(
                WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::cssSelector('nav div.navbar-collapse'))
            );
            $driver->wait()->until(
                WebDriverExpectedCondition::invisibilityOfElementLocated(WebDriverBy::cssSelector('nav span.navbar-toggler-icon'))
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
                WebDriverExpectedCondition::invisibilityOfElementLocated(WebDriverBy::cssSelector('nav div.navbar-collapse'))
            );
            $driver->wait()->until(
                WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::cssSelector('nav span.navbar-toggler-icon'))
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
        $driver->get('http://localhost:' . getenv('LOCAL_PORT'));

        // new window(tab)
        $driver->executeScript("window.open()");
        $handles = $driver->getWindowHandles();
        $driver->switchTo()->window(end($handles));
        $driver->get('http://localhost:' . getenv('LOCAL_PORT'));

        try {
            // 画面サイズを一旦MAXに
            $this->windowMaximize($driver);

            $driver->wait()->until(
                WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::cssSelector('nav div.navbar-collapse a.dropdown-toggle'))
            );
            $driver->findElement(WebDriverBy::cssSelector('nav div.navbar-collapse a.dropdown-toggle'))->click();

            $driver->wait()->until(
                WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::cssSelector('nav div.navbar-collapse > ul > li.nav-item.dropdown.show > div > a.dropdown-item.external-link'))
            );
            $driver->findElement(WebDriverBy::cssSelector('nav div.navbar-collapse > ul > li.nav-item.dropdown.show > div > a.dropdown-item.external-link'))->click();

        } catch (TimeOutException $e) {
            $this->fail($e->getMessage());
        }
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
        $driver->get('http://localhost:' . getenv('LOCAL_PORT'));

        // new window(tab)
        $driver->executeScript("window.open()");
        $handles = $driver->getWindowHandles();
        $driver->switchTo()->window(end($handles));
        $driver->get('http://localhost:' . getenv('LOCAL_PORT'));

        try {
            $this->windowSetSize($driver, $dimension);

            $driver->wait()->until(
                WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::cssSelector('nav span.navbar-toggler-icon'))
            );
            $driver->findElement(WebDriverBy::cssSelector('nav span.navbar-toggler-icon'))->click();

            $driver->wait()->until(
                WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::cssSelector('nav div.navbar-collapse a.dropdown-toggle'))
            );
            $driver->findElement(WebDriverBy::cssSelector('nav div.navbar-collapse a.dropdown-toggle'))->click();

            $driver->wait()->until(
                WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::cssSelector('nav div.navbar-collapse > ul > li.nav-item.dropdown.show > div > a.dropdown-item.external-link'))
            );
            $driver->findElement(WebDriverBy::cssSelector('nav div.navbar-collapse > ul > li.nav-item.dropdown.show > div > a.dropdown-item.external-link'))->click();

            $this->assertTrue(true);

        } catch (TimeOutException $e) {
            $this->fail($e->getMessage());
        }
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
        $driver->get('http://localhost:' . getenv('LOCAL_PORT'));

        // new window(tab)
        $driver->executeScript("window.open()");
        $handles = $driver->getWindowHandles();
        $driver->switchTo()->window(end($handles));
        $driver->get('http://localhost:' . getenv('LOCAL_PORT'));

        try {
            // 画面サイズを一旦MAXに
            $this->windowMaximize($driver);

            $driver->wait()->until(
                WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::cssSelector('nav div.navbar-collapse a.dropdown-toggle'))
            );
            $driver->findElement(WebDriverBy::cssSelector('nav div.navbar-collapse a.dropdown-toggle'))->click();

            $driver->wait()->until(
                WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::cssSelector('nav div.navbar-collapse > ul > li.nav-item.dropdown.show > div > a.dropdown-item.external-link'))
            );
            $driver->findElement(WebDriverBy::cssSelector('nav div.navbar-collapse > ul > li.nav-item.dropdown.show > div > a.dropdown-item.external-link'))->click();

        } catch (TimeOutException $e) {
            $this->fail($e->getMessage());
        }
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
        $driver->get('http://localhost:' . getenv('LOCAL_PORT'));

        // new window(tab)
        $driver->executeScript("window.open()");
        $handles = $driver->getWindowHandles();
        $driver->switchTo()->window(end($handles));
        $driver->get('http://localhost:' . getenv('LOCAL_PORT'));

        try {
            $this->windowSetSize($driver, $dimension);

            $driver->wait()->until(
                WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::cssSelector('nav span.navbar-toggler-icon'))
            );
            $driver->findElement(WebDriverBy::cssSelector('nav span.navbar-toggler-icon'))->click();

            $driver->wait()->until(
                WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::cssSelector('nav div.navbar-collapse a.dropdown-toggle'))
            );
            $driver->findElement(WebDriverBy::cssSelector('nav div.navbar-collapse a.dropdown-toggle'))->click();

            $driver->wait()->until(
                WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::cssSelector('nav div.navbar-collapse > ul > li.nav-item.dropdown.show > div > a.dropdown-item.external-link'))
            );
            $driver->findElement(WebDriverBy::cssSelector('nav div.navbar-collapse > ul > li.nav-item.dropdown.show > div > a.dropdown-item.external-link'))->click();

            $this->assertTrue(true);

        } catch (TimeOutException $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * IE 複数window(tab)を立ち上げ後にすべてのwindow(tab)が閉じられる
     * @test
     * @group ie
     */
    public function it_can_all_windows_closed_of_ie()
    {
        $cap = $this->createCapabilities(WebDriverBrowserType::IE);

        try {
            $driver = $this->createDriver($cap);
            $driver->get('http://localhost:' . getenv('LOCAL_PORT'));

            // 画面サイズを一旦MAXに
            $this->windowMaximize($driver);

            $driver->wait()->until(
                WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::cssSelector('nav div.navbar-collapse a.dropdown-toggle'))
            );
            $driver->findElement(WebDriverBy::cssSelector('nav div.navbar-collapse a.dropdown-toggle'))->click();

            $driver->wait()->until(
                WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::cssSelector('nav div.navbar-collapse > ul > li.nav-item.dropdown.show > div > a.dropdown-item.external-link'))
            );
            $driver->findElement(WebDriverBy::cssSelector('nav div.navbar-collapse > ul > li.nav-item.dropdown.show > div > a.dropdown-item.external-link'))->click();

            // 一旦全windowを閉じる
            $handles = $driver->getWindowHandles();
            foreach ($handles as $handle) {
                $driver->switchTo()->window($handle);
                $driver->close();
            }

        } catch (TimeOutException $e) {
            $this->fail($e->getMessage());
        }

        try {
            $driver = $this->createDriver($cap);
            $driver->get('http://localhost:' . getenv('LOCAL_PORT'));

            // 画面サイズを 414x736(iPhone6 Plus) に変更
            $dimension = $this->createDimension(['w' => 414, 'h' => 736]);
            $this->windowSetSize($driver, $dimension);

            $driver->wait()->until(
                WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::cssSelector('nav span.navbar-toggler-icon'))
            );
            $driver->findElement(WebDriverBy::cssSelector('nav span.navbar-toggler-icon'))->click();

            $driver->wait()->until(
                WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::cssSelector('nav div.navbar-collapse a.dropdown-toggle'))
            );
            $driver->findElement(WebDriverBy::cssSelector('nav div.navbar-collapse a.dropdown-toggle'))->click();

            $driver->wait()->until(
                WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::cssSelector('nav div.navbar-collapse > ul > li.nav-item.dropdown.show > div > a.dropdown-item.external-link'))
            );
            $driver->findElement(WebDriverBy::cssSelector('nav div.navbar-collapse > ul > li.nav-item.dropdown.show > div > a.dropdown-item.external-link'))->click();

            $this->assertTrue(true);

        } catch (TimeOutException $e) {
            $this->fail($e->getMessage());
        }
    }
}
