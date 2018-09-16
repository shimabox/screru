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

```
$ composer require shimabox/screru
$ cd screru
$ cp .env.example .env
```
or
```
$ git clone https://github.com/shimabox/screru.git
$ cd screru
$ composer install
$ cp .env.example .env
```

### ### macOS

- Operation confirmed in macOS High Sierra 10.13

#### selenium-server-standalone

- selenium-server-standalone 3.8.1
  - http://selenium-release.storage.googleapis.com/3.8/selenium-server-standalone-3.8.1.jar

#### geckodriver

- [geckodriver v0.21.0](https://github.com/mozilla/geckodriver/releases/tag/v0.21.0)
  - https://github.com/mozilla/geckodriver/releases/download/v0.21.0/geckodriver-v0.21.0-macos.tar.gz

```
$ tar -zxvf geckodriver-v0.21.0-macos.tar.gz
$ mv geckodriver /usr/local/bin/
$ chmod +x /usr/local/bin/geckodriver
```

#### chromedriver

- [chromedriver 2.41](https://chromedriver.storage.googleapis.com/index.html?path=2.41/ "")
  - https://chromedriver.storage.googleapis.com/2.41/chromedriver_mac64.zip

```
$ unzip chromedriver_mac64.zip
$ mv chromedriver /usr/local/bin/
$ chmod +x /usr/local/bin/chromedriver
```

#### .env

- Edit ```.env```
```
ENABLED_FIREFOX_DRIVER=true
ENABLED_CHROME_DRIVER=true
```

#### Run

1. Run selenium-server-standalone
```
$ java -jar selenium-server-standalone-3.8.1.jar -enablePassThrough false
```
2. Run phpunit
```
$ vendor/bin/phpunit
```

### ### windows(64bit)

#### selenium-server-standalone

- selenium-server-standalone 3.8.1
  - https://selenium-release.storage.googleapis.com/3.8/selenium-server-standalone-3.8.1.jar

#### geckodriver.exe

- [geckodriver v0.21.0](https://github.com/mozilla/geckodriver/releases/tag/v0.21.0)
  - https://github.com/mozilla/geckodriver/releases/download/v0.21.0/geckodriver-v0.21.0-win64.zip
    - Unzip the zip file...

#### chromedriver.exe

- [chromedriver 2.41](https://chromedriver.storage.googleapis.com/index.html?path=2.41/ "")
  - https://chromedriver.storage.googleapis.com/2.41/chromedriver_win32.zip
    - Unzip the zip file...

#### IEDriverServer.exe

- [IEDriverServer_x64_3.14.0.zip](https://selenium-release.storage.googleapis.com/index.html?path=3.14/)
  - https://selenium-release.storage.googleapis.com/3.14/IEDriverServer_Win32_3.14.0.zip
    - Key entry is faster here than 64 bit version(IEDriverServer_x64_3.14.0.zip)
    - Unzip the zip file...

#### .env

- Edit ```.env```
```
ENABLED_FIREFOX_DRIVER=true
ENABLED_CHROME_DRIVER=true
ENABLED_IE_DRIVER=true
// true to platform is windows
IS_PLATFORM_WINDOWS=true
// webdriver path for IE
FIREFOX_DRIVER_PATH='your geckodriver.exe path'
CHROME_DRIVER_PATH='your chromedriver.exe path'
IE_DRIVER_PATH='your IEDriverServer.exe path'
```

#### Run

1. Open ```cmd``` etc.
2. Run selenium-server-standalone
```shell
$ java -jar selenium-server-standalone-3.8.1.jar -enablePassThrough false
```
3. Open a new ```cmd``` etc.
4. Run phpunit
```
$ vendor/bin/phpunit
```

### ### Linux

- Operation confirmed in CentOS 6.9

#### java

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

#### Firefox

- install
```
$ sudo yum -y install firefox
```
or
```
$ sudo yum -y update firefox
```
- version 60.2.0
```
$ firefox -v
Mozilla Firefox 60.2.0
```

#### Xvfb

- install
```
$ sudo yum -y install xorg-x11-server-Xvfb
$ sudo yum -y groupinstall "Japanese Support"
```

#### selenium-server-standalone

- selenium-server-standalone 3.8.1
  - http://selenium-release.storage.googleapis.com/3.8/selenium-server-standalone-3.8.1.jar

#### geckodriver

- [geckodriver v0.21.0](https://github.com/mozilla/geckodriver/releases/tag/v0.21.0)
  - https://github.com/mozilla/geckodriver/releases/download/v0.21.0/geckodriver-v0.21.0-linux64.tar.gz

```
$ tar -zxvf geckodriver-v0.21.0-linux64.tar.gz
$ sudo mv geckodriver /usr/local/bin/
$ sudo chmod +x /usr/local/bin/geckodriver
```

#### .env

- Edit ```.env```
```
ENABLED_FIREFOX_DRIVER=true
```

#### Run

1. Run Xvfb & selenium-server-standalone
```
$ sudo sh start_selenium.sh
```
2. Run phpunit
```
$ vendor/bin/phpunit
```
3. Stop Xvfb & selenium-server-standalone & geckodriver
```
$ sudo sh kill_selenium.sh
```

## Usage

- Basic Usage

```php
require_once '/vendor/autoload.php';

use SMB\Screru\Factory\DesiredCapabilities;
use SMB\UrlStatus;

use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\WebDriverBrowserType;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Facebook\WebDriver\WebDriverBy;

// Create firefox webdriver
$host = getenv('SELENIUM_SERVER_URL');
$cap = new DesiredCapabilities(WebDriverBrowserType::FIREFOX);
$driver = RemoteWebDriver::create($host, $cap->get());

$url = 'https://www.google.com/webhp?gl=us&hl=en&gws_rd=cr';

// 指定URLへ遷移 (Google)
$driver->get($url);

// 検索Box
$findElement = $driver->findElement(WebDriverBy::name('q'));
// 検索Boxにキーワードを入力して
$findElement->sendKeys('Hello');
// 検索実行
$findElement->submit();

// 検索結果画面のタイトルが 'Hello - Google Search' になるまで10秒間待機する
// 指定したタイトルにならずに10秒以上経ったら
// 'Facebook\WebDriver\Exception\TimeOutException' がthrowされる
$driver->wait(10)->until(
    WebDriverExpectedCondition::titleIs('Hello - Google Search')
);

// Hello - Google Search というタイトルが取得できることを確認する
if ($driver->getTitle() !== 'Hello - Google Search') {
    throw new Exception('fail $driver->getTitle()');
}

// HttpStatus of url
$status = UrlStatus::get($driver->getCurrentURL());
if ($status->is200() === false) {
    throw new Exception('fail HttpStatus');
}

// close window
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

- ``` $ php example/example_1.php ```
- ``` $ php example/example_2.php ```
  - For headless chrome.
- ``` $ php example/example_3.php ```
  - For headless firefox.

## Testing

### Start PHP's built-in Web Server

- It is necessary for functional test

```
$ php -S 127.0.0.1:8000 -t tests/functional/web
```

### Run

```
$ vendor/bin/phpunit
```

- To exclude functional tests
```
$ vendor/bin/phpunit --exclude-group functional
```

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
