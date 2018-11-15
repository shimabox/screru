<?php

require_once realpath(__DIR__ . '/../vendor') . '/autoload.php';

use SMB\Screru\Factory\DesiredCapabilities;
use SMB\Screru\Screenshot\Screenshot;
use SMB\Screru\Elements\Spec;
use SMB\Screru\Elements\SpecPool;
use SMB\UrlStatus;

use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\WebDriverBrowserType;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverDimension;
use Facebook\WebDriver\WebDriverExpectedCondition;

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

    // When setting the screen size to MAX.
    // $driver->manage()->window()->maximize();

    // When the screen size is specified.
    if (isset($size['w']) && isset($size['h'])) {
        $dimension = new WebDriverDimension($size['w'], $size['h']);
        $driver->manage()->window()->setSize($dimension);
    }

    $url = 'https://www.google.com/webhp?gl=us&hl=en&gws_rd=cr';

    // Transit to designated URL (Google).
    $driver->get($url);

    // Search for elements.
    $findElement = $driver->findElement(WebDriverBy::name('q'));
    // Enter keywords in search box.
    $findElement->sendKeys('Hello');
    // Search execution.
    $findElement->submit();

    // Wait until the contents are visualized(Targeting '#botstuff').
    $driver->wait(10, 100)->until(
        WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::id('botstuff'))
    );

    // Capture settings.
    $fileName = $overrideUA === '' ? __METHOD__ . "_{$browser}" : __METHOD__ . "_sp_{$browser}";
    $ds = DIRECTORY_SEPARATOR;
    $captureDirectoryPath = realpath(__DIR__ . $ds . 'capture') . $ds;

    // Create a Screenshot.
    $screenshot = new Screenshot();

    // Full screen capture (extension will be .png).
    $screenshot->takeFull($driver, $captureDirectoryPath, $fileName.'_full.png');

    // Change specified element with 'pc' and 'sp'.
    $selector = $overrideUA === '' ? '.RNNXgb' : '#sfcnt';
    $selector2 = $overrideUA === '' ? '.brs_col' : '.uUPGi';

    // Define element selector.
    $spec = new Spec($selector, Spec::GREATER_THAN_OR_EQUAL, 1);
    $spec2 = new Spec($selector2, Spec::GREATER_THAN, 1);

    // Push into SpecPool.
    $specPool = (new SpecPool())
                ->addSpec($spec)
                ->addSpec($spec2);

    // Element capture (extension is .png).
    $screenshot->takeElement($driver, $captureDirectoryPath, $fileName, $specPool);

    // HttpStatus of url
    $status = UrlStatus::get($driver->getCurrentURL());
    if ($status->is200() === false) {
        throw new Exception('fail');
    }

    // Close window.
    $driver->close();
}

// Size of iPhone 6.
$size4iPhone6 = ['w' => 375, 'h' => 667];

// UserAgent of iOS12.
$ua4iOS = 'Mozilla/5.0 (iPhone; CPU iPhone OS 12_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/12.0 Mobile/15E148 Safari/604.1';

/**
 |------------------------------------------------------------------------------
 | Please set the value of the driver you want to enable to true.
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
