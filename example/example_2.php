<?php

require_once realpath(__DIR__ . '/../vendor') . '/autoload.php';

use SMB\Screru\Factory\DesiredCapabilities;
use SMB\Screru\Screenshot\Screenshot;

use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\WebDriverBrowserType;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverDimension;

/**
 * example 2
 *
 * Sample Headless Chrome
 *
 * @param string $browser 'chrome'
 * @param array $size ['w' => xxx, 'h' => xxx]
 * @param string overrideUA true : override Useragent
 * @param boolean $headless true : headless mode
 */
function example_2($browser, array $size=[], $overrideUA = '', $headless=true)
{
    // selenium
    $host = getenv('SELENIUM_SERVER_URL');

    $cap = new DesiredCapabilities($browser);

    if ($overrideUA !== '') {
        $cap->setUserAgent($overrideUA);
    }

    // 画面サイズの指定あり
    $dimension = null;
    if (isset($size['w']) && isset($size['h'])) {
        $dimension = new WebDriverDimension($size['w'], $size['h']);
    }

    // ヘッドレスモード時でサイズの指定あり
    if ($dimension !== null && $headless) {
        $cap->setWindowSizeInHeadless($dimension);
    }

    $driver = RemoteWebDriver::create($host, $cap->get());

    if ($dimension !== null) {
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
    $suffix = $headless ? '_headless' : '_not-headless';
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

// iOS10のUA
$ua4iOS = 'Mozilla/5.0 (iPhone; CPU iPhone OS 10_0_1 like Mac OS X) AppleWebKit/602.1.50 (KHTML, like Gecko) Version/10.0 Mobile/14A403 Safari/602.1';

// only chrome
if (getenv('ENABLED_CHROME_DRIVER') === 'true') {
    // headless
    example_2(WebDriverBrowserType::CHROME);
    example_2(WebDriverBrowserType::CHROME, $size4iPhone6, $ua4iOS);

    // not headless
    example_2(WebDriverBrowserType::CHROME, [], '', false);
    example_2(WebDriverBrowserType::CHROME, $size4iPhone6, $ua4iOS, false);
}
