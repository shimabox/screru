# Screru
Screru is a library that supplements php-webdriver

[![License](https://poser.pugx.org/shimabox/screru/license)](https://packagist.org/packages/shimabox/screru)
[![Build Status](https://travis-ci.org/shimabox/screru.svg?branch=master)](https://travis-ci.org/shimabox/screru)
[![Maintainability](https://api.codeclimate.com/v1/badges/127a74e984d2a8014fe8/maintainability)](https://codeclimate.com/github/shimabox/screru/maintainability)
[![Coverage Status](https://coveralls.io/repos/github/shimabox/screru/badge.svg?branch=master)](https://coveralls.io/github/shimabox/screru?branch=master)
[![Latest Stable Version](https://poser.pugx.org/shimabox/screru/v/stable)](https://packagist.org/packages/shimabox/screru)
[![Latest Unstable Version](https://poser.pugx.org/shimabox/screru/v/unstable)](https://packagist.org/packages/shimabox/screru)

## Description

Screru is a library that supplements php-webdriver.
It provides the following functions.

- Trait for PHPUnit is available
- Full screenshot
  - Capture can be done when the assertion fails
- Screenshot of element

Supports Firefox (WebDriverBrowserType::FIREFOX), Chrome (WebDriverBrowserType::CHROME) and IE (WebDriverBrowserType::IE).

## Demo

![demo](https://github.com/shimabox/assets/raw/master/screru/demo.gif)

## Requirements

- PHP 5.6+ or newer
- [Composer](https://getcomposer.org)
- Java(JDK) >=1.8
  - http://www.oracle.com/technetwork/java/javase/downloads/index.html

## Installation

### Via composer.

```
$ composer require shimabox/screru
$ cd vendor/shimabox/screru
$ cp .env.default .env
```

### Develop.

```
$ git clone https://github.com/shimabox/screru.git
$ cd screru
$ composer install
```

- Copy the `.env.default` file and create an `.env` file.

## Setting (.env | .env.default)

If you need to change the default settings, copy the `.env.default` file, create an `.env` file, and modify the `.env` file.  
The default setting looks at `.env.default` file.

```
$ vim .env

// selenium server url
SELENIUM_SERVER_URL='http://localhost:4444/wd/hub'
// you can override the default User-agent
OVERRIDE_DEFAULT_USER_AGENT=''
// local port
LOCAL_PORT=8000
// true to enable
ENABLED_CHROME_DRIVER=true
ENABLED_FIREFOX_DRIVER=true
ENABLED_IE_DRIVER=
// true to start headless chrome
ENABLED_CHROME_HEADLESS=true
// true to start headless firefox
ENABLED_FIREFOX_HEADLESS=true
// true to platform is windows
IS_PLATFORM_WINDOWS=
// describe the webdriver path if necessary
CHROME_DRIVER_PATH=''
FIREFOX_DRIVER_PATH=''
IE_DRIVER_PATH=''
```

|Key|Description|Default|Example|
|:---|:---|:---|:---|
|SELENIUM_SERVER_URL|selenium server url.|http://localhost:4444/wd/hub||
|OVERRIDE_DEFAULT_USER_AGENT|you can override the default User-agent.|Mozilla/5.0 (iPhone; CPU iPhone OS 12_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/12.0 Mobile/15E148 Safari/604.1||
|LOCAL_PORT|local port<br>It is for unittest.|8000||
|ENABLED_CHROME_DRIVER|If true it will enable ChromeDriver.|true||
|ENABLED_FIREFOX_DRIVER|If true it will enable geckodriver.|true||
|ENABLED_IE_DRIVER|If true it will enable IEDriverServer.|blank(false)||
|ENABLED_CHROME_HEADLESS|true to start headless chrome.|true||
|ENABLED_FIREFOX_HEADLESS|true to start headless firefox.|true||
|IS_PLATFORM_WINDOWS|true to platform is windows.<br>For windows, be sure to set to true.|blank(false)||
|CHROME_DRIVER_PATH|Describe the webdriver path if necessary.|blank|/home/user/screru/chromedriver|
|FIREFOX_DRIVER_PATH|Describe the webdriver path if necessary.|blank|/Applications/MAMP/htdocs/screru/geckodriver|
|IE_DRIVER_PATH|Describe the webdriver path if necessary.|blank|/c/xampp/htdocs/screru/IEDriverServer.exe|

## Preparation

Download selenium-server-standalone, ChromeDriver, geckodriver, IEDriverServer etc.

|Platform|selenium-server-standalone|ChromeDriver|geckodriver|IEDriverServer|
|:---|:---|:---|:---|:---|
|Mac|[3.8.1](https://selenium-release.storage.googleapis.com/3.8/selenium-server-standalone-3.8.1.jar)|[79.0.3945.36](https://chromedriver.storage.googleapis.com/79.0.3945.36/chromedriver_mac64.zip)|[0.26.0](https://github.com/mozilla/geckodriver/releases/download/v0.26.0/geckodriver-v0.26.0-macos.tar.gz)|-|
|Windows(64bit)|[3.8.1](https://selenium-release.storage.googleapis.com/3.8/selenium-server-standalone-3.8.1.jar)|[75.0.3770.90](https://chromedriver.storage.googleapis.com/75.0.3770.90/chromedriver_win32.zip)|[0.24.0](https://github.com/mozilla/geckodriver/releases/download/v0.24.0/geckodriver-v0.24.0-win64.zip)|[3.141.59](https://selenium-release.storage.googleapis.com/3.141/IEDriverServer_Win32_3.141.59.zip)|
|Linux(CentOS 6.9)|[3.8.1](https://selenium-release.storage.googleapis.com/3.8/selenium-server-standalone-3.8.1.jar)|-|[0.24.0](https://github.com/mozilla/geckodriver/releases/download/v0.24.0/geckodriver-v0.24.0-linux64.tar.gz)|-|
|Linux(Ubuntu trusty)|[3.8.1](https://selenium-release.storage.googleapis.com/3.8/selenium-server-standalone-3.8.1.jar)|[75.0.3770.90](https://chromedriver.storage.googleapis.com/75.0.3770.90/chromedriver_linux64.zip)|[0.24.0](https://github.com/mozilla/geckodriver/releases/download/v0.24.0/geckodriver-v0.24.0-linux64.tar.gz)|-|

### Use downloader.

- e.g) For Mac.
```
$ php selenium_downloader.php -p m -d . -s 3.8.1 -c 79.0.3945.36 -g 0.26.0
```
- e.g) For Windows.
```
$ php selenium_downloader.php -p w -d . -s 3.8.1 -c 2.43 -g 0.23.0 -i 3.14.0
```
- e.g) For Linux.
```
$ php selenium_downloader.php -p l -d . -s 3.8.1 -g 0.23.0
```

@see [selenium-downloader/README.md at master · shimabox/selenium-downloader · GitHub](https://github.com/shimabox/selenium-downloader/blob/master/README.md "selenium-downloader/README.md at master · shimabox/selenium-downloader · GitHub")

## ## macOS

- Add the path to ChromeDriver and geckodriver to your Path environment variable.
  - e.g) ChromeDriver
  ```
  $ mv chromedriver /usr/local/bin/
  $ chmod +x /usr/local/bin/chromedriver
  ```
  - e.g) geckodriver
  ```
  $ mv geckodriver /usr/local/bin/
  $ chmod +x /usr/local/bin/geckodriver
  ```
- Or, write the driver's path in the `.env` file  
Please give execute permission (`chmod +x`)

e.g)

```
CHROME_DRIVER_PATH=/Applications/MAMP/htdocs/screru/chromedriver
FIREFOX_DRIVER_PATH=/Applications/MAMP/htdocs/screru/geckodriver
```

### Run selenium-server-standalone.

```
$ java -jar selenium-server-standalone-3.8.1.jar -enablePassThrough false
```

## ## windows(64bit)

### .env

- Edit ```.env```
```
ENABLED_FIREFOX_DRIVER=true
ENABLED_CHROME_DRIVER=true
ENABLED_IE_DRIVER=true
// true to platform is windows
IS_PLATFORM_WINDOWS=true
// describe the webdriver path if necessary
FIREFOX_DRIVER_PATH='your geckodriver.exe path'
CHROME_DRIVER_PATH='your chromedriver.exe path'
IE_DRIVER_PATH='your IEDriverServer.exe path'
```

The value of `IS_PLATFORM_WINDOWS` must be set to `true`.

### Run selenium-server-standalone.

```
$ java -jar selenium-server-standalone-3.8.1.jar -enablePassThrough false
```

#### Note.

```
Facebook\WebDriver\Exception\SessionNotCreatedException: Unexpected error launching Internet Explorer.
Protected Mode settings are not the same for all zones.
Enable Protected Mode must be set to the same value (enabled or disabled) for all zones.
```

When this error is displayed, please refer to the following link.

- [Rantings of a Selenium Contributor: You're Doing It Wrong: IE Protected Mode and WebDriver](http://jimevansmusic.blogspot.com/2012/08/youre-doing-it-wrong-protected-mode-and.html "Rantings of a Selenium Contributor: You're Doing It Wrong: IE Protected Mode and WebDriver")
- [internet settings.png (2718×1068)](https://storage.googleapis.com/google-code-attachments/selenium/issue-1795/comment-66/internet%20settings.png "internet settings.png (2718×1068)")

It is solved by setting the security mode of IE Internet option to ON in all zones.

## ## Linux (CentOS 6.9)

### java

- install
```
$ sudo yum -y install java
```
- version 1.8>=
```
$ java -version
openjdk version "1.8.0_131"
OpenJDK Runtime Environment (build 1.8.0_131-b11)
OpenJDK 64-Bit Server VM (build 25.131-b11, mixed mode)
```

### Firefox

- install
```
$ sudo yum -y install firefox
```
or
```
$ sudo yum -y update firefox
```
- version 60.3.0
```
$ firefox -v
Mozilla Firefox 60.3.0
```

### Xvfb

- install
```
$ sudo yum -y install xorg-x11-server-Xvfb
$ sudo yum -y groupinstall "Japanese Support"
```

### Path

- Add the path to geckodriver to your Path environment variable.
```
$ mv geckodriver /usr/local/bin/
$ chmod +x /usr/local/bin/geckodriver
```
- Or, write the driver's path in the `.env` file  
Please give execute permission (`chmod +x`)

e.g)

```
FIREFOX_DRIVER_PATH=/home/user/screru/geckodriver
```

### .env

- Edit ```.env```
```
ENABLED_FIREFOX_DRIVER=true
ENABLED_CHROME_DRIVER=
ENABLED_IE_DRIVER=
```

### Run selenium-server-standalone.

1. Run Xvfb & selenium-server-standalone
```
$ sudo sh start_selenium.sh
```
2. Stop Xvfb & selenium-server-standalone & geckodriver
```
$ sudo sh kill_selenium.sh
```

## ## Linux (Ubuntu trusty)

Please refer to this setting.  
- [shimabox/screru - Travis CI](https://travis-ci.org/shimabox/screru "shimabox/screru - Travis CI")

## Usage

- Basic Usage

```php
<?php
require_once '/vendor/autoload.php';

use SMB\Screru\Elements\Spec;
use SMB\Screru\Elements\SpecPool;
use SMB\Screru\Factory\DesiredCapabilities;
use SMB\Screru\Screenshot\Screenshot;
use SMB\UrlStatus;

use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\WebDriverBrowserType;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Facebook\WebDriver\WebDriverBy;

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
```

- When you want to change the default selenium server url

```
$ vim .env

// selenium server url
SELENIUM_SERVER_URL='your selenium server url'
```

- When you want to change the default UserAgent

```
$ vim .env

// you can override the default User-agent (Android 7.1.1)
OVERRIDE_DEFAULT_USER_AGENT='Mozilla/5.0 (Linux; Android 7.1.1; Nexus 5X Build/N4F26I) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.91 Mobile Safari/537.36'
```

- When using with PHPUnit
  - ```use \SMB\Screru\Traits\Testable```
  ```php
  class Sample extends \PHPUnit_Framework_TestCase
  {
      // use Trait
      use \SMB\Screru\Traits\Testable {
          setUp as protected traitSetUp;
          tearDown as protected traitTearDown;
      }

      /**
       * setUp
       */
      protected function setUp()
      {
          $this->traitSetUp();
      }

      /**
       * tearDown
       */
      protected function tearDown()
      {
          $this->traitTearDown();
      }

      // do someting ...
  }
  ```

  - If you want to capture when an assertion fails
    - ```takeCaptureWhenAssertionFails = true;```
    ```php
    class Sample extends \PHPUnit_Framework_TestCase
    {
        // use Trait
        use \SMB\Screru\Traits\Testable {
            setUp as protected traitSetUp;
            tearDown as protected traitTearDown;
        }

        // Set this property to true
        protected $takeCaptureWhenAssertionFails = true;

        /**
         * setUp
         */
        protected function setUp()
        {
            $this->traitSetUp();
        }

        /**
         * tearDown
         */
        protected function tearDown()
        {
            $this->traitTearDown();
        }

        // do someting ...
    }
    ```
    - or call the function below.
    ```php
    $this->enableCaptureWhenAssertionFails();

    // To disable, call the following function
    $this->disableCaptureWhenAssertionFails();
    ```

## Headless Chrome

For the latest chrome, you can use headless mode.

- Edit ```.env```
```
// true to start headless chrome
ENABLED_CHROME_HEADLESS=true
```

## Headless Firefox

For the latest firefox, you can use headless mode.

- Edit ```.env```
```
// true to start headless firefox
ENABLED_FIREFOX_HEADLESS=true
```

## Example

- `$ php example/assertion_of_title.php`
  - [example/assertion_of_title.php](https://github.com/shimabox/screru/blob/master/example/assertion_of_title.php)
    - Execute a title assertion.
- `$ php example/example.php`
  - [example/example.php](https://github.com/shimabox/screru/blob/master/example/example.php)
    1. Transit to designated URL (Google).
    2. Perform fullscreen capture.
    3. Perform screen element capture.
- `$ php example/headless_chrome.php`
  - [example/headless_chrome.php](https://github.com/shimabox/screru/blob/master/example/headless_chrome.php)
    1. Transit to designated URL (Google).
    2. Perform fullscreen capture.
- `$ php example/headless_firefox.php`
  - [example/headless_firefox.php](https://github.com/shimabox/screru/blob/master/example/headless_firefox.php)
    1. Transit to designated URL (Google).
    2. Perform fullscreen capture.
- `$ php example/screenshot.php`
  - [example/screenshot.php](https://github.com/shimabox/screru/blob/master/example/screenshot.php)
    1. Transit to designated URL (Google).
    2. Perform fullscreen capture.
    3. Perform screen capture.
- `$ php example/element_screenshot.php`
  - [example/element_screenshot.php](https://github.com/shimabox/screru/blob/master/example/element_screenshot.php)
    1. Transit to designated URL (Google).
    2. Perform screen element capture.

## Testing

### Start PHP's built-in Web Server.

```
$ php -S 127.0.0.1:8000 -t tests/web
```

Match port with `.env` - `LOCAL_PORT`.

### Run test.

```
$ vendor/bin/phpunit
```

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
