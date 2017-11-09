<?php

require_once realpath(__DIR__ . '/../vendor') . '/autoload.php';

use SMB\Screru\Factory\DesiredCapabilities;
use SMB\Screru\Screenshot\Screenshot;

use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\WebDriverBrowserType;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverDimension;

/**
 * example 3
 *
 * Sample Headless Firefox
 *
 * @param string $browser 'firefox'
 * @param array $size ['w' => xxx, 'h' => xxx]
 * @param string overrideUA override Useragent
 */
function example_3($browser, array $size=[], $overrideUA = '')
{
    // headless?
    $isHeadless = getenv('ENABLED_FIREFOX_HEADLESS') === 'true';

    // selenium
    $host = getenv('SELENIUM_SERVER_URL');

    $cap = new DesiredCapabilities($browser);

    if ($overrideUA !== '') {
        $cap->setUserAgent($overrideUA);
    }

    $driver = RemoteWebDriver::create($host, $cap->get());

    // 画面サイズの指定あり
    if (isset($size['w']) && isset($size['h'])) {
        $dimension = new WebDriverDimension($size['w'], $size['h']);
        $driver->manage()->window()->setSize($dimension);
    }

    $url = 'https://www.google.com/webhp?gl=us&hl=en&gws_rd=cr';

    // 指定URLへ遷移 (Google)
    $driver->get($url);

    // 検索Box
    $findElement = $driver->findElement(WebDriverBy::name('q'));
    // 検索Boxにキーワードを入力して
    $findElement->sendKeys('Hello');
    // 検索実行
    $findElement->submit();

    // キャプチャ
    $suffix = $isHeadless ? '_headless' : '_not-headless';
    $fileName = $overrideUA === '' ? __METHOD__ . "_pc" . $suffix : __METHOD__ . "_sp" . $suffix;
    $ds = DIRECTORY_SEPARATOR;
    $captureDirectoryPath = realpath(__DIR__ . $ds . 'capture') . $ds;

    // create Screenshot
    $screenshot = new Screenshot();

    // 全画面キャプチャ (ファイル名は拡張子あり / png になります)
    $screenshot->takeFull($driver, $captureDirectoryPath, $fileName . '.png', $browser);

    // ブラウザを閉じる
    $driver->close();
}

// iPhone6のサイズ
$size4iPhone6 = ['w' => 375, 'h' => 667];

// iOS11のUA
$ua4iOS = 'Mozilla/5.0 (iPhone; CPU iPhone OS 11_0 like Mac OS X) AppleWebKit/604.1.38 (KHTML, like Gecko) Version/11.0 Mobile/15A372 Safari/604.1';

// only firefox
if (getenv('ENABLED_FIREFOX_DRIVER') === 'true') {

    // headless
    putenv('ENABLED_FIREFOX_HEADLESS=true');
    example_3(WebDriverBrowserType::FIREFOX);
    example_3(WebDriverBrowserType::FIREFOX, $size4iPhone6, $ua4iOS);

    // not headless
    putenv('ENABLED_FIREFOX_HEADLESS=');
    example_3(WebDriverBrowserType::FIREFOX);
    example_3(WebDriverBrowserType::FIREFOX, $size4iPhone6, $ua4iOS);
}
