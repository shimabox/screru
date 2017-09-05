<?php

require_once realpath(__DIR__ . '/../vendor') . '/autoload.php';

use SMB\Screru\Wrapper\RemoteWebDriver;
use SMB\Screru\Factory\DesiredCapabilities;
use SMB\Screru\Screenshot\Screenshot;
use SMB\Screru\Elements\Spec;
use SMB\Screru\Elements\SpecPool;
use SMB\UrlStatus;

use Facebook\WebDriver\Remote\WebDriverBrowserType;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverDimension;

/**
 * example 1
 * @param string $browser 'chrome' or 'firefox' or 'internet explorer'
 * @param array $size ['w' => xxx, 'h' => xxx]
 * @param string overrideUA true : override Useragent
 */
function example_1($browser, array $size=[], $overrideUA = '')
{
    // selenium
    $host = getenv('SELENIUM_SERVER_URL');

    switch ($browser) {
        case WebDriverBrowserType::CHROME :
            $cap = new DesiredCapabilities($browser);

            if ($overrideUA !== '') {
                $cap->setUserAgent($overrideUA);
            }

            $driver = RemoteWebDriver::create($host, $cap->get());

            break;
        case WebDriverBrowserType::FIREFOX :
            $cap = new DesiredCapabilities($browser);

            if ($overrideUA !== '') {
                $cap->setUserAgent($overrideUA);
            }

            $driver = RemoteWebDriver::create($host, $cap->get());

            break;
        case WebDriverBrowserType::IE :
            $cap = new DesiredCapabilities($browser);
            $driver = RemoteWebDriver::create($host, $cap->get());
            break;
    }

    // 画面サイズをMAXにする場合
    // $driver->manage()->window()->maximize();

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
    $fileName = $overrideUA === '' ? __METHOD__ . "_{$browser}" : __METHOD__ . "_sp_{$browser}";
    $ds = DIRECTORY_SEPARATOR;
    $captureDirectoryPath = realpath(__DIR__ . $ds . 'capture') . $ds;

    // create Screenshot
    $screenshot = new Screenshot();

    // 全画面キャプチャ (ファイル名は拡張子あり / png になります)
    $screenshot->takeFull($driver, $captureDirectoryPath, $fileName.'_full.png', $browser);

    // pc と sp で指定要素を変える
    $selector = $overrideUA === '' ? '.rc' : '#rso > div > div.mnr-c';
    $selector2 = $overrideUA === '' ? '.brs_col' : 'a._bCp';

    // 要素のセレクターを定義して
    $spec = new Spec($selector, Spec::GREATER_THAN_OR_EQUAL, 1);
    $spec2 = new Spec($selector2, Spec::GREATER_THAN, 1);

    // SpecPoolに突っ込む
    $specPool = (new SpecPool())
                ->addSpec($spec)
                ->addSpec($spec2);

    // 要素のキャプチャ (ファイル名は拡張子無し / pngになります)
    $screenshot->takeElement($driver, $captureDirectoryPath, $fileName, $browser, $specPool);

    // HttpStatus of url
    $status = UrlStatus::get($driver->getCurrentURL());
    if ($status->is200() === false) {
        throw new Exception('fail');
    }

    // ブラウザを閉じる
    $driver->close();
}

// iPhone6のサイズ
$size4iPhone6 = ['w' => 375, 'h' => 667];

// iOS10のUA
$ua4iOS = 'Mozilla/5.0 (iPhone; CPU iPhone OS 10_0_1 like Mac OS X) AppleWebKit/602.1.50 (KHTML, like Gecko) Version/10.0 Mobile/14A403 Safari/602.1';

/**
 |------------------------------------------------------------------------------
 | 有効にしたいドライバーの値を true にしてください
 |------------------------------------------------------------------------------
 */

// chrome
if (getenv('ENABLED_CHROME_DRIVER') === 'true') {
    example_1(WebDriverBrowserType::CHROME);
    example_1(WebDriverBrowserType::CHROME, $size4iPhone6, $ua4iOS);
}

// firefox
if (getenv('ENABLED_FIREFOX_DRIVER') === 'true') {
    example_1(WebDriverBrowserType::FIREFOX);
    example_1(WebDriverBrowserType::FIREFOX, $size4iPhone6, $ua4iOS);
}

// ie
if (getenv('ENABLED_IE_DRIVER') === 'true') {
    example_1(WebDriverBrowserType::IE);
}
