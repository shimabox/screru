<?php

namespace SMB\Screru\Traits;

use SMB\Screru\Wrapper\RemoteWebDriver;
use SMB\Screru\Factory\DesiredCapabilities;
use SMB\Screru\Exception\DisabledWebDriverException;
use SMB\Screru\Exception\NotSpecifiedWebDriverException;
use SMB\Screru\Screenshot\Screenshot;
use SMB\Screru\Elements\SpecPool;

use Facebook\WebDriver\WebDriverDimension;
use Facebook\WebDriver\Exception\TimeOutException;
use Facebook\WebDriver\Exception\NoSuchWindowException;
use Facebook\WebDriver\Exception\WebDriverCurlException;

/**
 * Testable
 * 
 * \PHPUnit_Framework_TestCaseを継承したクラスでuseしてください
 * 
 * <code>
 *  class Base extends \PHPUnit_Framework_TestCase
 *  {
 *      // alias for function...
 *      use \SMB\Screru\Traits\Testable {
 *          setUp as protected traitSetUp;
 *          tearDown as protected traitTearDown;
 *      }
 *  }
 * </code>
 */
trait Testable
{
    /**
     * selenium server url
     * @var string
     */
    protected $seleniumServerUrl = '';

    /**
     * DesiredCapabilities
     * @var \Facebook\WebDriver\Remote\DesiredCapabilities
     */
    protected $capabilities;

    /**
     * Screenshot
     * @var \SMB\Screru\Screenshot\Screenshot
     */
    protected $screenshot;

    /**
     * テスト(assert)失敗時にキャプチャを撮るかどうか
     * @var boolean
     */
    protected $takeCaptureWhenAssertionFails = false;

    /**
     * Wrapper of RemoteWebDriver
     * @var \SMB\Screru\Wrapper\RemoteWebDriver
     */
    private $driver;

    /**
     * コンストラクタ
     */
    public function __construct()
    {
        if ($this->seleniumServerUrl === '') {
            $this->seleniumServerUrl = getenv('SELENIUM_SERVER_URL');
        }
    }

    /**
     * setUp
     */
    protected function setUp()
    {
        parent::setUp();
    }

    /**
     * tearDown
     */
    protected function tearDown()
    {
        parent::tearDown();

        // If you want to run closeDriver(), overwride tearDown()
        $this->quitDriver();
    }

    /**
     * driver quit
     * 
     * quit() will close all windows
     */
    protected function quitDriver()
    {
        if (!$this->isRunningDriver()) {
            return;
        }

        if ($this->takeCaptureWhenAssertionFails() === true) {
            $this->doTakeCaptureWhenAssertionFails();
        }

        try {
            $this->driver->quit();
        } catch (NoSuchWindowException $e) {
            // browser may have died
        }
        $this->driver = null;
    }

    /**
     * driver close
     * 
     * close() will close the current window
     */
    protected function closeDriver()
    {
        if (!$this->isRunningDriver()) {
            return;
        }

        if ($this->takeCaptureWhenAssertionFails() === true) {
            $this->doTakeCaptureWhenAssertionFails();
        }

        try {
            $this->driver->close();
        } catch (NoSuchWindowException $e) {
            // browser may have died
        } catch (WebDriverCurlException $e) {
            // Workaround for `Facebook\WebDriver\Exception\WebDriverCurlException: Curl error thrown for http DELETE`
        }

        $this->driver = null;
    }

    /**
     * webdriverが動いているかどうか
     * @return boolean
     */
    protected function isRunningDriver()
    {
        if (
            ! $this->driver instanceof RemoteWebDriver
            || $this->driver->isClosed() === true
            || $this->driver->isQuit() === true
        ) {
            return false;
        }

        return true;
    }

    /**
     * RemoteWebDriver 生成
     * @param \SMB\Screru\Factory\DesiredCapabilities $cap
     * @param \Facebook\WebDriver\WebDriverDimension $dimension
     * @return \SMB\Screru\Wrapper\RemoteWebDriver
     */
    protected function createDriver(
        DesiredCapabilities $cap      = null,
        WebDriverDimension $dimension = null
    )
    {
        if ($cap === null) {
            $cap = $this->createCapabilities();
        }

        // ヘッドレスモード時でサイズの指定があるか
        if ($dimension !== null && getenv('ENABLED_CHROME_HEADLESS') === 'true') {
            $cap->setWindowSizeInHeadless($dimension);
        }

        /* @var \Facebook\WebDriver\Remote\DesiredCapabilities */
        $this->capabilities = $cap->get();

        // ドライバーの起動
        $driver = RemoteWebDriver::create($this->seleniumServerUrl, $this->capabilities, 60 * 1000, 60 * 1000);

        // サイズの指定があるか
        if ($dimension !== null) {
            $driver->manage()->window()->setSize($dimension);
        }

        $this->driver = $driver;

        return $this->driver;
    }

    /**
     * 画面サイズをMAXに
     * @param \SMB\Screru\Wrapper\RemoteWebDriver $driver
     */
    protected function windowMaximize(RemoteWebDriver $driver)
    {
        $driver->manage()->window()->maximize();
    }

    /**
     * 画面サイズ変更
     * @param \SMB\Screru\Wrapper\RemoteWebDriver $driver
     * @param \Facebook\WebDriver\WebDriverDimension $dimension
     */
    protected function windowSetSize(RemoteWebDriver $driver, WebDriverDimension $dimension)
    {
        $driver->manage()->window()->setSize($dimension);
    }

    /**
     * DesiredCapabilities 生成
     * @param string $browser
     * @return \SMB\Screru\Factory\DesiredCapabilities
     */
    protected function createCapabilities($browser='')
    {
        try {
            return new DesiredCapabilities($browser);
        } catch (DisabledWebDriverException $e) {
            // テスト対象でないWebDriverの場合skipしておく
            $this->markTestSkipped($e->getMessage());
        } catch (NotSpecifiedWebDriverException $e) {
            // 対象のWebDriverが設定されていなければskipしておく
            $this->markTestSkipped($e->getMessage());
        }
    }

    /**
     * WebDriverDimension 生成
     * @param array $size ['w' => xxx, 'h' => xxx]
     * @return \Facebook\WebDriver\WebDriverDimension
     */
    protected function createDimension(array $size)
    {
        return new WebDriverDimension($size['w'], $size['h']);
    }

    /**
     * スクリーンショット
     * @param \SMB\Screru\Wrapper\RemoteWebDriver $driver
     * @param string $filename Without extension
     * @param int $sleep Sleep for seconds
     * @param string $dir capture以下に階層が必要だったら階層を追加
     * @return string キャプチャ画像パス
     */
    protected function takeScreenshot(RemoteWebDriver $driver, $filename, $sleep=1, $dir='')
    {
        $path = $this->capturePath($dir);
        return $this->createScreenshot()->take($driver, $path . $filename, $sleep);
    }

    /**
     * 全画面スクリーンショット
     * @param \SMB\Screru\Wrapper\RemoteWebDriver $driver
     * @param string $filename Without extension
     * @param int $sleep Sleep for seconds
     * @param string $dir capture以下に階層が必要だったら階層を追加
     * @return string キャプチャ画像パス
     */
    protected function takeFullScreenshot(RemoteWebDriver $driver, $filename, $sleep=1, $dir='')
    {
        $path = $this->capturePath($dir);
        return $this->createScreenshot()->takeFull($driver, $path, $filename, $sleep);
    }

    /**
     * 指定された要素のスクリーンショット
     * @param \SMB\Screru\Wrapper\RemoteWebDriver $driver
     * @param string $filename Without extension
     * @param \SMB\Screru\Elements\SpecPool $specPool
     * @param int $sleep Sleep for seconds
     * @param string $dir capture以下に階層が必要だったら階層を追加
     * @return array キャプチャ画像パス
     */
    protected function takeElementScreenshot(RemoteWebDriver $driver, $filename, SpecPool $specPool, $sleep=1, $dir='')
    {
        $path = $this->capturePath($dir);
        try {
            return $this->createScreenshot()->takeElement($driver, $path, $filename, $specPool, $sleep);
        } catch (TimeOutException $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * アサーションが失敗したときのキャプチャ撮影を有効にする
     */
    protected function enableCaptureWhenAssertionFails()
    {
        $this->takeCaptureWhenAssertionFails = true;
    }

    /**
     * アサーションが失敗したときのキャプチャ撮影を無効にする
     */
    protected function disableCaptureWhenAssertionFails()
    {
        $this->takeCaptureWhenAssertionFails = false;
    }

    /**
     * テスト(assert)失敗時にキャプチャを撮るか判定
     * @return boolean true: take a capture when assertion fails
     */
    protected function takeCaptureWhenAssertionFails()
    {
        if (
            $this->takeCaptureWhenAssertionFails === true
            && $this->getStatus() === \PHPUnit_Runner_BaseTestRunner::STATUS_FAILURE
        ) {
            return true;
        }

        return false;
    }

    /**
     * テスト(assert)失敗時にキャプチャを撮る
     */
    protected function doTakeCaptureWhenAssertionFails()
    {
        $this->takeFullScreenshot($this->driver, (new \DateTime())->format('YmdHis') . '_' . $this->getName(), 0, 'fail');
    }

    /**
     * create Screenshot
     * @return \SMB\Screru\Screenshot\Screenshot
     */
    protected function createScreenshot()
    {
        if ($this->screenshot !== null) {
            return $this->screenshot;
        }

        $this->screenshot = new Screenshot();
        return $this->screenshot;
    }

    /**
     * キャプチャ保存用のパスを返す
     * @param string $dir capture以下の階層
     * @return string
     */
    protected function capturePath($dir='')
    {
        $ds = DIRECTORY_SEPARATOR;
        $_dir = $dir === '' ? '' : trim($dir, $ds) . $ds;
        return realpath(__DIR__ . "{$ds}..{$ds}..{$ds}tests{$ds}capture") . $ds . $_dir;
    }
}
