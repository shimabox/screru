<?php

require_once realpath(__DIR__ . '/../vendor') . '/autoload.php';

use SMB\Screru\Factory\DesiredCapabilities;
use SMB\Screru\Screenshot\Screenshot;

use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\WebDriverBrowserType;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverDimension;
use Facebook\WebDriver\WebDriverExpectedCondition;

/**
 * example 2
 *
 * Sample Headless Chrome
 *
 * @param string $browser 'chrome'
 * @param array $size ['w' => xxx, 'h' => xxx]
 * @param string overrideUA override Useragent
 */
function example_2($browser, array $size=[], $overrideUA = '')
{
    // headless?
    $isHeadless = getenv('ENABLED_CHROME_HEADLESS') === 'true';

    // selenium
    $host = getenv('SELENIUM_SERVER_URL');

    $cap = new DesiredCapabilities($browser);

    if ($overrideUA !== '') {
        $cap->setUserAgent($overrideUA);
    }

    // When the screen size is specified.
    $dimension = null;
    if (isset($size['w']) && isset($size['h'])) {
        $dimension = new WebDriverDimension($size['w'], $size['h']);
    }

    // When there is a specified size in headless mode.
    if ($dimension !== null && $isHeadless) {
        $cap->setWindowSizeInHeadless($dimension);
    }

    $driver = RemoteWebDriver::create($host, $cap->get());

    if ($dimension !== null) {
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
    $suffix = $isHeadless ? '_headless' : '_not-headless';
    $fileName = $overrideUA === '' ? __METHOD__ . "_pc" . $suffix : __METHOD__ . "_sp" . $suffix;
    $ds = DIRECTORY_SEPARATOR;
    $captureDirectoryPath = realpath(__DIR__ . $ds . 'capture') . $ds;

    // Create a Screenshot.
    $screenshot = new Screenshot();

    // Full screen capture (extension will be .png).
    $screenshot->takeFull($driver, $captureDirectoryPath, $fileName . '.png');

    // Close window.
    $driver->close();
}

// Size of iPhone 6.
$size4iPhone6 = ['w' => 375, 'h' => 667];

//  UserAgent of iOS12.
$ua4iOS = 'Mozilla/5.0 (iPhone; CPU iPhone OS 12_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/12.0 Mobile/15E148 Safari/604.1';

// Only chrome.
if (getenv('ENABLED_CHROME_DRIVER') === 'true') {

    // headless
    putenv('ENABLED_CHROME_HEADLESS=true');
    example_2(WebDriverBrowserType::CHROME);
    example_2(WebDriverBrowserType::CHROME, $size4iPhone6, $ua4iOS);

    // not headless
    putenv('ENABLED_CHROME_HEADLESS=');
    example_2(WebDriverBrowserType::CHROME);
    example_2(WebDriverBrowserType::CHROME, $size4iPhone6, $ua4iOS);
}
