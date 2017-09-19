# Screru
Screru is a library that supplements php-webdriver

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

- PHP 5.5+ or newer
- [Composer](https://getcomposer.org)
- Java(JDK) >=1.8
  - http://www.oracle.com/technetwork/java/javase/downloads/index.html
- [facebook/php-webdriver](https://github.com/facebook/php-webdriver "facebook/php-webdriver: A php client for webdriver.")
- [vlucas/phpdotenv](https://github.com/vlucas/phpdotenv "vlucas/phpdotenv: Loads environment variables from `.env` to `getenv()`, `$_ENV` and `$_SERVER` automagically.")
- [shimabox/url-status](https://github.com/shimabox/url-status "shimabox/url-status: Passing the url returns the status by looking at the header information")

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
- version 52.3.0
```
$ firefox -v
Mozilla Firefox 52.3.0
```

#### Xvfb

- install
```
$ sudo yum -y install xorg-x11-server-Xvfb
$ sudo yum -y groupinstall "Japanese Support"
```

#### selenium-server-standalone

- selenium-server-standalone 3.3.1
  - http://selenium-release.storage.googleapis.com/3.3/selenium-server-standalone-3.3.1.jar

#### geckodriver

- [geckodriver v0.15.0](https://github.com/mozilla/geckodriver/releases/tag/v0.15.0)
  - https://github.com/mozilla/geckodriver/releases/download/v0.15.0/geckodriver-v0.15.0-linux64.tar.gz

```
$ tar -zxvf geckodriver-v0.15.0-linux64.tar.gz
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

### ### macOS

- Operation confirmed in macOS Sierra 10.12.4

#### selenium-server-standalone

- selenium-server-standalone 3.4.0
  - http://selenium-release.storage.googleapis.com/3.4/selenium-server-standalone-3.4.0.jar

#### geckodriver

- [geckodriver v0.17.0](https://github.com/mozilla/geckodriver/releases/tag/v0.17.0)
  - https://github.com/mozilla/geckodriver/releases/download/v0.17.0/geckodriver-v0.17.0-macos.tar.gz

```
$ tar -zxvf geckodriver-v0.17.0-macos.tar.gz
$ mv geckodriver /usr/local/bin/
$ chmod +x /usr/local/bin/geckodriver
```

#### chromedriver

- [chromedriver 2.31](https://chromedriver.storage.googleapis.com/index.html?path=2.31/ "")
  - https://chromedriver.storage.googleapis.com/2.31/chromedriver_mac64.zip

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
$ java -jar selenium-server-standalone-3.4.0.jar
```
2. Run phpunit
```
$ vendor/bin/phpunit
```

### ### windows(64bit)

#### selenium-server-standalone

- selenium-server-standalone 3.4.0
  - http://selenium-release.storage.googleapis.com/3.4/selenium-server-standalone-3.4.0.jar

#### geckodriver.exe

- [geckodriver v0.17.0](https://github.com/mozilla/geckodriver/releases/tag/v0.17.0)
  - https://github.com/mozilla/geckodriver/releases/download/v0.17.0/geckodriver-v0.17.0-win64.zip
    - Unzip the zip file...

#### chromedriver.exe

- [chromedriver 2.31](https://chromedriver.storage.googleapis.com/index.html?path=2.31/ "")
  - https://chromedriver.storage.googleapis.com/2.31/chromedriver_win32.zip
    - Unzip the zip file...

#### IEDriverServer.exe

- [IEDriverServer_x64_3.4.0.zip](http://selenium-release.storage.googleapis.com/index.html?path=3.4/)
  - http://selenium-release.storage.googleapis.com/3.4/IEDriverServer_x64_3.4.0.zip
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
$ java -jar selenium-server-standalone-3.4.0.jar
```
3. Open a new ```cmd``` etc.
4. Run phpunit
```
$ vendor/bin/phpunit
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

      // do somting ...
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

        // do somting ...
    }
    ```

## Headless Chrome

For the latest chrome, you can use headless mode.

- Edit ```.env```
```
// true to start headless chrome
ENABLED_CHROME_HEADLESS=true
```

- sample
```php
require_once '/vendor/autoload.php';

use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\WebDriverBrowserType;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverDimension;
use Facebook\WebDriver\Chrome\ChromeOptions;

use SMB\Screru\Screenshot\Screenshot;

/**
 * sample headless
 *
 * java -Dwebdriver.chrome.driver="$CHROMEDRIVER_PATH" -jar selenium-server-standalone-3.5.3.jar
 *
 * @param array $size ['w' => xxx, 'h' => xxx]
 * @param string overrideUA true : override Useragent
 * @param boolean $headless true : headless mode
 */
function sample(array $size=[], $overrideUA = '', $headless=true)
{
    // selenium
    $host = 'http://localhost:4444/wd/hub';

    $cap = DesiredCapabilities::chrome();
    $options = new ChromeOptions();

    // true, headless mode
    if ($headless === true) {
        $options->addArguments(['--headless']);

        // 画面サイズの指定あり
        // headlessの場合、$driver->manage()->window()->setSize(); で画面サイズ変更が出来ない？
        if (isset($size['w']) && isset($size['h'])) {
            $options->addArguments(["window-size={$size['w']},{$size['h']}"]);
        }
    }

    // forge useragent
    if ($overrideUA !== '') {
        $options->addArguments(['--user-agent=' . $overrideUA]);
    }

    $cap->setCapability(ChromeOptions::CAPABILITY, $options);

    // ドライバーの起動
    $driver = RemoteWebDriver::create($host, $cap);

    // 画面サイズをMAXにする
    $driver->manage()->window()->maximize();

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
    $suffix = $headless ? '_headless' : '_not-headless';
    $fileName = $overrideUA === '' ? __METHOD__ . "_pc" . $suffix : __METHOD__ . "_sp" . $suffix;

    // create Screenshot
    $screenshot = new Screenshot();

    // 全画面キャプチャ (ファイル名は拡張子あり / png になります)
    $screenshot->takeFull($driver, __DIR__, $fileName . '.png', WebDriverBrowserType::CHROME);

    // ブラウザを閉じる
    $driver->close();
}

/*
 |------------------------------------------------------------------------------
 | 処理実行
 |------------------------------------------------------------------------------
 */

// iPhone6のサイズ
$size4iPhone6 = ['w' => 375, 'h' => 667];

// iOS10のUA
$ua4iOS = 'Mozilla/5.0 (iPhone; CPU iPhone OS 10_0_1 like Mac OS X) AppleWebKit/602.1.50 (KHTML, like Gecko) Version/10.0 Mobile/14A403 Safari/602.1';

// pc
sample();

// sp
sample($size4iPhone6, $ua4iOS);

// not headless mode pc
sample([], '', false);

// not headless mode sp
sample($size4iPhone6, $ua4iOS, false);
```


## Example

- ``` $ php example/example_1.php ```

## Testing

```
$ vendor/bin/phpunit
```

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
