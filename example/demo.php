<?php
require_once realpath(__DIR__ . '/../vendor') . '/autoload.php';

use SMB\Screru\Elements\Spec;
use SMB\Screru\Elements\SpecPool;
use SMB\Screru\Factory\DesiredCapabilities;
use SMB\Screru\Screenshot\Screenshot;
use SMB\UrlStatus;

use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\WebDriverBrowserType;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverDimension;
use Facebook\WebDriver\WebDriverExpectedCondition;

if (getenv('ENABLED_CHROME_DRIVER') !== 'true') {
    die('Please enable ChromeDriver.');
}

$host = getenv('SELENIUM_SERVER_URL');
// Use chromedriver.
$cap = new DesiredCapabilities(WebDriverBrowserType::CHROME);
$driver = RemoteWebDriver::create($host, $cap->get());

// Window size.
$w = 600;
$h = 800;
$dimension = new WebDriverDimension($w, $h);
$driver->manage()->window()->setSize($dimension);

$url = 'https://www.google.com/webhp?gl=us&hl=en&gws_rd=cr';

// Transit to designated URL (Google).
$driver->get($url);

// Search for elements.
$findElement = $driver->findElement(WebDriverBy::name('q'));
// Enter keywords in search box.
$findElement->sendKeys('Hello');
// Search execution.
$findElement->submit();

// Wait 10 seconds for the contents to be visualized(Targeting '#botstuff').
// If the specified element does not appear and it takes more than 10 seconds,
// 'Facebook\WebDriver\Exception\TimeOutException' is thrown.
$driver->wait(10)->until(
    WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::id('botstuff'))
);

// Confirm that the title "Hello - Google search" can be obtained
if ($driver->getTitle() !== 'Hello - Google Search') {
    throw new Exception('fail $driver->getTitle()');
}

// HttpStatus of url
$status = UrlStatus::get($driver->getCurrentURL());
if ($status->is200() === false) {
    throw new Exception('fail HttpStatus');
}

/*
 |------------------------------------------------------------------------------
 | Capture test.
 |------------------------------------------------------------------------------
 */

$fileName = 'capture_demo';
$ds = DIRECTORY_SEPARATOR;
$captureDirectoryPath = realpath(__DIR__ . $ds . 'capture') . $ds;

// Create a Screenshot.
$screenshot = new Screenshot();

// Full screen capture (extension will be .png).
$screenshot->takeFull($driver, $captureDirectoryPath, $fileName . '_full.png');

// Define element selector.
$spec = new Spec('.RNNXgb', Spec::GREATER_THAN_OR_EQUAL, 1);
$spec2 = new Spec('.brs_col', Spec::GREATER_THAN, 1);

// Push into SpecPool.
$specPool = (new SpecPool())
            ->addSpec($spec)
            ->addSpec($spec2);

// Element capture (extension is .png).
$screenshot->takeElement($driver, $captureDirectoryPath, $fileName, $specPool);

// Close window.
$driver->close();
