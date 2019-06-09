<?php

require_once realpath(__DIR__ . '/../vendor') . '/autoload.php';

use SMB\Screru\Factory\DesiredCapabilities;

use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\WebDriverBrowserType;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverDimension;
use Facebook\WebDriver\WebDriverExpectedCondition;

/**
 * Assertion of title
 * 
 * @param string $browser 'chrome'
 * @param array $size ['w' => xxx, 'h' => xxx]
 * @param string overrideUA true : override Useragent
 */
function assertion_of_title($browser, array $size=[], $overrideUA = '')
{
    // selenium
    $host = getenv('SELENIUM_SERVER_URL');

    $cap = new DesiredCapabilities($browser);

    if ($overrideUA !== '') {
        $cap->setUserAgent($overrideUA);
    }

    $driver = RemoteWebDriver::create($host, $cap->get());

    // When setting the screen size to MAX.
    if (empty($size)) {
        $driver->manage()->window()->maximize();
    }

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

    // Wait 10 seconds for the search results screen title to be 'Hello - Google Search'
    // 'Facebook\WebDriver\Exception\TimeOutException' will be thrown 
    // if 10 seconds pass without getting the specified title.
    $driver->wait(10)->until(
        WebDriverExpectedCondition::titleIs('Hello - Google Search')
    );

    // Verify that you can get the title 'Hello-Google Search'.
    if ($driver->getTitle() !== 'Hello - Google Search') {
        throw new Exception('fail');
    }

    // Close window.
    $driver->close();
}

// Size of iPhone 6/7/8.
$size4iPhone = ['w' => 375, 'h' => 667];

// UserAgent of iOS12.
$ua4iOS = 'Mozilla/5.0 (iPhone; CPU iPhone OS 12_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/12.0 Mobile/15E148 Safari/604.1';

/**
 |------------------------------------------------------------------------------
 | Please set the value of the driver you want to enable to true.
 |------------------------------------------------------------------------------
 */

// Only chrome.
if (getenv('ENABLED_CHROME_DRIVER') === 'true') {

    // headless
    putenv('ENABLED_CHROME_HEADLESS=true');
    assertion_of_title(WebDriverBrowserType::CHROME);
    assertion_of_title(WebDriverBrowserType::CHROME, $size4iPhone, $ua4iOS);

    // not headless
    putenv('ENABLED_CHROME_HEADLESS=');
    assertion_of_title(WebDriverBrowserType::CHROME);
    assertion_of_title(WebDriverBrowserType::CHROME, $size4iPhone, $ua4iOS);
}
