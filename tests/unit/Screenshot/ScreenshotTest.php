<?php

namespace SMB\Screru\Tests\Screenshot;

use SMB\Screru\Screenshot\Screenshot;
use SMB\Screru\Elements\Spec;
use SMB\Screru\Elements\SpecPool;

use Facebook\WebDriver\Remote\WebDriverBrowserType;

/**
 * Test of SMB\Screru\Screenshot\Screenshot
 *
 * @group Screenshot
 */
class ScreenshotTest extends \PHPUnit_Framework_TestCase
{
    use \SMB\Screru\Traits\Testable {
        setUp as protected traitSetUp;
        tearDown as protected traitTearDown;
    }

    /** @var \SMB\Screru\Screenshot\Screenshot */
    private $target;

    /**
     * setUp
     */
    protected function setUp()
    {
        parent::setUp();

        $this->target = new Screenshot();
    }

    /**
     * PC:Chrome フルスクリーンキャプチャが撮れる
     * @test
     * @group chrome
     */
    public function it_can_take_fullscreen_of_pc_chrome()
    {
        $path = $this->capturePath();
        $captureFileName = 'it_can_take_fullscreen_of_pc_chrome.png';
        $byTakeScreenshotFileName = 'it_can_take_a_capture_of_pc_chrome_by_takeScreenshot.png';

        $targetCaptureFiles = [
            $path . $captureFileName,
            $path . $byTakeScreenshotFileName
        ];

        $this->deleteImageFiles($targetCaptureFiles);

        $cap = $this->createCapabilities(WebDriverBrowserType::CHROME);
        $dimension = $this->createDimension(['w' => 375, 'h' => 667]);

        $driver = $this->createDriver($cap, $dimension);
        $driver->get('https://www.google.com/search?gl=us&hl=en&gws_rd=cr&q=hello');

        $actual = $this->target->takeFull($driver, $path, $captureFileName, $this->capabilities->getBrowserName(), 1);
        $this->assertFileExists($actual);
        $this->assertSame($path . $captureFileName, $actual);
        
        // $driver->takeScreenshot()
        $byTakeScreenshot = $this->target->take($driver, $path . $byTakeScreenshotFileName);
        $this->assertFileExists($byTakeScreenshot);
        $this->assertSame($path . $byTakeScreenshotFileName, $byTakeScreenshot);

        // $driver->takeScreenshot() で撮れた画像サイズと比較する
        list($fullWidth, $fullHeight) = getimagesize($actual);
        list($byTakeScreenshotWidth, $byTakeScreenshotHeight) = getimagesize($byTakeScreenshot);

        $this->assertTrue($fullWidth > $byTakeScreenshotWidth);
        $this->assertTrue($fullHeight > $byTakeScreenshotHeight);
        
        $this->deleteImageFiles($targetCaptureFiles);
    }

    /**
     * SP(UA偽装):Chrome フルスクリーンキャプチャが撮れる
     * @test
     * @group chrome
     */
    public function it_can_take_fullscreen_of_forge_ua_sp_chrome()
    {
        $path = $this->capturePath();
        $captureFileName = 'it_can_take_fullscreen_of_forge_ua_sp_chrome.png';
        $byTakeScreenshotFileName = 'it_can_take_a_capture_of_forge_ua_sp_chrome_by_takeScreenshot.png';

        $targetCaptureFiles = [
            $path . $captureFileName,
            $path . $byTakeScreenshotFileName
        ];

        $this->deleteImageFiles($targetCaptureFiles);

        $cap = $this->createCapabilities(WebDriverBrowserType::CHROME);
        $cap->settingDefaultUserAgent();

        $dimension = $this->createDimension(['w' => 375, 'h' => 667]);

        $driver = $this->createDriver($cap, $dimension);
        $driver->get('https://www.google.com/search?gl=us&hl=en&gws_rd=cr&q=hello');

        $actual = $this->target->takeFull($driver, $path, $captureFileName, $this->capabilities->getBrowserName(), 1);
        $this->assertFileExists($actual);
        $this->assertSame($path . $captureFileName, $actual);

        // $driver->takeScreenshot()
        $byTakeScreenshot = $this->target->take($driver, $path . $byTakeScreenshotFileName);
        $this->assertFileExists($byTakeScreenshot);
        $this->assertSame($path . $byTakeScreenshotFileName, $byTakeScreenshot);

        // $driver->takeScreenshot() で撮れた画像サイズと比較する
        list($fullWidth, $fullHeight) = getimagesize($actual);
        list($byTakeScreenshotWidth, $byTakeScreenshotHeight) = getimagesize($byTakeScreenshot);

        $this->assertTrue($fullWidth === $byTakeScreenshotWidth); // width は一緒のはず
        $this->assertTrue($fullHeight > $byTakeScreenshotHeight);

        $this->deleteImageFiles($targetCaptureFiles);
    }

    /**
     * PC:Firefox フルスクリーンキャプチャが撮れる
     * @test
     * @group firefox
     */
    public function it_can_take_fullscreen_of_pc_firefox()
    {
        $path = $this->capturePath();
        $captureFileName = 'it_can_take_fullscreen_of_pc_firefox.png';
        $byTakeScreenshotFileName = 'it_can_take_a_capture_of_pc_firefox_by_takeScreenshot.png';

        $targetCaptureFiles = [
            $path . $captureFileName,
            $path . $byTakeScreenshotFileName
        ];

        $this->deleteImageFiles($targetCaptureFiles);

        $cap = $this->createCapabilities(WebDriverBrowserType::FIREFOX);
        $dimension = $this->createDimension(['w' => 375, 'h' => 667]);

        $driver = $this->createDriver($cap, $dimension);
        $driver->get('https://www.google.com/search?gl=us&hl=en&gws_rd=cr&q=hello');

        $actual = $this->target->takeFull($driver, $path, $captureFileName, $this->capabilities->getBrowserName(), 1);
        $this->assertFileExists($actual);
        $this->assertSame($path . $captureFileName, $actual);

        // $driver->takeScreenshot()
        $byTakeScreenshot = $this->target->take($driver, $path . $byTakeScreenshotFileName);
        $this->assertFileExists($byTakeScreenshot);
        $this->assertSame($path . $byTakeScreenshotFileName, $byTakeScreenshot);

        // $driver->takeScreenshot() で撮れた画像サイズと比較する
        list($fullWidth, $fullHeight) = getimagesize($actual);
        list($byTakeScreenshotWidth, $byTakeScreenshotHeight) = getimagesize($byTakeScreenshot);

        $this->assertTrue($fullWidth > $byTakeScreenshotWidth);
        $this->assertTrue($fullHeight > $byTakeScreenshotHeight);

        $this->deleteImageFiles($targetCaptureFiles);
    }

    /**
     * SP(UA偽装):Firefox フルスクリーンキャプチャが撮れる
     * @test
     * @group firefox
     */
    public function it_can_take_fullscreen_of_forge_ua_sp_firefox()
    {
        $path = $this->capturePath();
        $captureFileName = 'it_can_take_fullscreen_of_forge_ua_sp_firefox.png';
        $byTakeScreenshotFileName = 'it_can_take_a_capture_of_forge_ua_sp_firefox_by_takeScreenshot.png';

        $targetCaptureFiles = [
            $path . $captureFileName,
            $path . $byTakeScreenshotFileName
        ];

        $this->deleteImageFiles($targetCaptureFiles);

        $cap = $this->createCapabilities(WebDriverBrowserType::FIREFOX);
        $cap->settingDefaultUserAgent();

        $dimension = $this->createDimension(['w' => 375, 'h' => 667]);

        $driver = $this->createDriver($cap, $dimension);
        $driver->get('https://www.google.com/search?gl=us&hl=en&gws_rd=cr&q=hello');

        $actual = $this->target->takeFull($driver, $path, $captureFileName, $this->capabilities->getBrowserName(), 1);
        $this->assertFileExists($actual);
        $this->assertSame($path . $captureFileName, $actual);

        // $driver->takeScreenshot()
        $byTakeScreenshot = $this->target->take($driver, $path . $byTakeScreenshotFileName);
        $this->assertFileExists($byTakeScreenshot);
        $this->assertSame($path . $byTakeScreenshotFileName, $byTakeScreenshot);

        // $driver->takeScreenshot() で撮れた画像サイズと比較する
        list($fullWidth, $fullHeight) = getimagesize($actual);
        list($byTakeScreenshotWidth, $byTakeScreenshotHeight) = getimagesize($byTakeScreenshot);

        $this->assertTrue($fullWidth === $byTakeScreenshotWidth); // width は一緒のはず
        $this->assertTrue($fullHeight > $byTakeScreenshotHeight);

        $this->deleteImageFiles($targetCaptureFiles);
    }

    /**
     * PC:IE フルスクリーンキャプチャが撮れる
     * @test
     * @group ie
     */
    public function it_can_take_fullscreen_of_pc_ie()
    {
        $path = $this->capturePath();
        $captureFileName = 'it_can_take_fullscreen_of_pc_ie.png';
        $byTakeScreenshotFileName = 'it_can_take_a_capture_of_pc_ie_by_takeScreenshot.png';

        $targetCaptureFiles = [
            $path . $captureFileName,
            $path . $byTakeScreenshotFileName
        ];

        $this->deleteImageFiles($targetCaptureFiles);

        $cap = $this->createCapabilities(WebDriverBrowserType::IE);
        $dimension = $this->createDimension(['w' => 375, 'h' => 667]);

        $driver = $this->createDriver($cap, $dimension);
        $driver->get('https://www.google.com/search?gl=us&hl=en&gws_rd=cr&q=hello');

        $actual = $this->target->takeFull($driver, $path, $captureFileName, $this->capabilities->getBrowserName(), 1);
        $this->assertFileExists($actual);
        $this->assertSame($path . $captureFileName, $actual);

        // $driver->takeScreenshot()
        $byTakeScreenshot = $this->target->take($driver, $path . $byTakeScreenshotFileName);
        $this->assertFileExists($byTakeScreenshot);
        $this->assertSame($path . $byTakeScreenshotFileName, $byTakeScreenshot);

        // $driver->takeScreenshot() で撮れた画像サイズと比較する
        list($fullWidth, $fullHeight) = getimagesize($actual);
        list($byTakeScreenshotWidth, $byTakeScreenshotHeight) = getimagesize($byTakeScreenshot);

        $this->assertTrue($fullWidth > $byTakeScreenshotWidth);
        $this->assertTrue($fullHeight > $byTakeScreenshotHeight);

        $this->deleteImageFiles($targetCaptureFiles);
    }

    /**
     * PC:Chrome 要素のキャプチャが撮れる
     * @test
     * @group chrome
     */
    public function it_can_take_element_of_pc_chrome()
    {
        $path = $this->capturePath();
        $captureFileName = 'it_can_take_element_of_pc_chrome';
        $targetCaptureFiles = [
            $path . $captureFileName . '_0_0.png', // #nav > tbody
            $path . $captureFileName . '_1_0.png', // #nav > tbody > tr > td
            $path . $captureFileName . '_1_1.png',
            $path . $captureFileName . '_1_2.png',
            $path . $captureFileName . '_1_3.png',
            $path . $captureFileName . '_1_4.png',
            $path . $captureFileName . '_1_5.png',
            $path . $captureFileName . '_1_6.png',
            $path . $captureFileName . '_1_7.png',
            $path . $captureFileName . '_1_8.png',
            $path . $captureFileName . '_1_9.png',
            $path . $captureFileName . '_1_10.png',
            $path . $captureFileName . '_1_11.png',
        ];

        $this->deleteImageFiles($targetCaptureFiles);

        $cap = $this->createCapabilities(WebDriverBrowserType::CHROME);

        $driver = $this->createDriver($cap);
        $driver->get('https://www.google.com/search?gl=us&hl=en&gws_rd=cr&q=hello');

        // セレクター
        $selector  = '#nav > tbody';
        $selector2 = '#nav > tbody > tr > td';

        // 要素のセレクターを定義して
        $spec = new Spec($selector, Spec::EQUAL, 1);
        $spec2 = new Spec($selector2, Spec::GREATER_THAN, 10);

        // SpecPoolに突っ込む
        $specPool = (new SpecPool())
                    ->addSpec($spec)
                    ->addSpec($spec2);

        $actual = $this->target->takeElement($driver, $path, $captureFileName, $this->capabilities->getBrowserName(), $specPool, 1);

        $this->assertSame($targetCaptureFiles, $actual);
        foreach ($targetCaptureFiles as $file) {
            $this->assertFileExists($file);
        }

        $this->deleteImageFiles($targetCaptureFiles);
    }

    /**
     * SP(UA偽装):Chrome 要素のキャプチャが撮れる
     * @test
     * @group chrome
     */
    public function it_can_take_element_of_forge_ua_sp_chrome()
    {
        $path = $this->capturePath();
        $captureFileName = 'it_can_take_element_of_forge_ua_sp_chrome';
        $targetCaptureFiles = [
            $path . $captureFileName . '_0_0.png', // #fbar
            $path . $captureFileName . '_1_0.png', // #botstuff div._Qot > div > a
            $path . $captureFileName . '_1_1.png',
            $path . $captureFileName . '_1_2.png',
            $path . $captureFileName . '_1_3.png',
            $path . $captureFileName . '_1_4.png',
            $path . $captureFileName . '_1_5.png',
            $path . $captureFileName . '_1_6.png',
            $path . $captureFileName . '_1_7.png',
        ];

        $this->deleteImageFiles($targetCaptureFiles);

        $cap = $this->createCapabilities(WebDriverBrowserType::CHROME);
        $cap->settingDefaultUserAgent();

        $dimension = $this->createDimension(['w' => 375, 'h' => 667]);

        $driver = $this->createDriver($cap, $dimension);
        $driver->get('https://www.google.com/search?gl=us&hl=en&gws_rd=cr&q=hello');

        // セレクター
        $selector  = '#fbar';
        $selector2 = '#botstuff div._Qot > div > a';

        // 要素のセレクターを定義して
        $spec = new Spec($selector, Spec::EQUAL, 1);
        $spec2 = new Spec($selector2, Spec::GREATER_THAN_OR_EQUAL, 8);

        // SpecPoolに突っ込む
        $specPool = (new SpecPool())
                    ->addSpec($spec)
                    ->addSpec($spec2);

        $actual = $this->takeElementScreenshot($driver, $captureFileName, $specPool);

        $this->assertSame($targetCaptureFiles, $actual);
        foreach ($targetCaptureFiles as $file) {
            $this->assertFileExists($file);
        }

        $this->deleteImageFiles($targetCaptureFiles);
    }

    /**
     * PC:Firefox 要素のキャプチャが撮れる
     * @test
     * @group firefox
     */
    public function it_can_take_element_of_pc_firefox()
    {
        $path = $this->capturePath();
        $captureFileName = 'it_can_take_element_of_pc_firefox';
        $targetCaptureFiles = [
            $path . $captureFileName . '_0_0.png', // #nav > tbody
            $path . $captureFileName . '_1_0.png', // #nav > tbody > tr > td
            $path . $captureFileName . '_1_1.png',
            $path . $captureFileName . '_1_2.png',
            $path . $captureFileName . '_1_3.png',
            $path . $captureFileName . '_1_4.png',
            $path . $captureFileName . '_1_5.png',
            $path . $captureFileName . '_1_6.png',
            $path . $captureFileName . '_1_7.png',
            $path . $captureFileName . '_1_8.png',
            $path . $captureFileName . '_1_9.png',
            $path . $captureFileName . '_1_10.png',
            $path . $captureFileName . '_1_11.png',
        ];

        $this->deleteImageFiles($targetCaptureFiles);

        $cap = $this->createCapabilities(WebDriverBrowserType::FIREFOX);

        $driver = $this->createDriver($cap);
        $driver->get('https://www.google.com/search?gl=us&hl=en&gws_rd=cr&q=hello');

        // セレクター
        $selector  = '#nav > tbody';
        $selector2 = '#nav > tbody > tr > td';

        // 要素のセレクターを定義して
        $spec = new Spec($selector, Spec::EQUAL, 1);
        $spec2 = new Spec($selector2, Spec::GREATER_THAN, 10);

        // SpecPoolに突っ込む
        $specPool = (new SpecPool())
                    ->addSpec($spec)
                    ->addSpec($spec2);

        $actual = $this->target->takeElement($driver, $path, $captureFileName, $this->capabilities->getBrowserName(), $specPool, 1);

        $this->assertSame($targetCaptureFiles, $actual);
        foreach ($targetCaptureFiles as $file) {
            $this->assertFileExists($file);
        }

        $this->deleteImageFiles($targetCaptureFiles);
    }

    /**
     * SP(UA偽装):Firefox 要素のキャプチャが撮れる
     * @test
     * @group firefox
     */
    public function it_can_take_element_of_forge_ua_sp_firefox()
    {
        $path = $this->capturePath();
        $captureFileName = 'it_can_take_element_of_forge_ua_sp_chrome';
        $targetCaptureFiles = [
            $path . $captureFileName . '_0_0.png', // #fbar
            $path . $captureFileName . '_1_0.png', // #botstuff div._Qot > div > a
            $path . $captureFileName . '_1_1.png',
            $path . $captureFileName . '_1_2.png',
            $path . $captureFileName . '_1_3.png',
            $path . $captureFileName . '_1_4.png',
            $path . $captureFileName . '_1_5.png',
            $path . $captureFileName . '_1_6.png',
            $path . $captureFileName . '_1_7.png',
        ];

        $this->deleteImageFiles($targetCaptureFiles);

        $cap = $this->createCapabilities(WebDriverBrowserType::FIREFOX);
        $cap->settingDefaultUserAgent();

        $dimension = $this->createDimension(['w' => 375, 'h' => 667]);

        $driver = $this->createDriver($cap, $dimension);
        $driver->get('https://www.google.com/search?gl=us&hl=en&gws_rd=cr&q=hello');

        // セレクター
        $selector  = '#fbar';
        $selector2 = '#botstuff div._Qot > div > a';

        // 要素のセレクターを定義して
        $spec = new Spec($selector, Spec::EQUAL, 1);
        $spec2 = new Spec($selector2, Spec::GREATER_THAN_OR_EQUAL, 8);

        // SpecPoolに突っ込む
        $specPool = (new SpecPool())
                    ->addSpec($spec)
                    ->addSpec($spec2);

        $actual = $this->takeElementScreenshot($driver, $captureFileName, $specPool);

        $this->assertSame($targetCaptureFiles, $actual);
        foreach ($targetCaptureFiles as $file) {
            $this->assertFileExists($file);
        }

        $this->deleteImageFiles($targetCaptureFiles);
    }

    /**
     * PC:IE 要素のキャプチャが撮れる
     * @test
     * @group ie
     */
    public function it_can_take_element_of_pc_ie()
    {
        $path = $this->capturePath();
        $captureFileName = 'it_can_take_element_of_pc_ie';
        $targetCaptureFiles = [
            $path . $captureFileName . '_0_0.png', // #nav > tbody
            $path . $captureFileName . '_1_0.png', // #nav > tbody > tr > td
            $path . $captureFileName . '_1_1.png',
            $path . $captureFileName . '_1_2.png',
            $path . $captureFileName . '_1_3.png',
            $path . $captureFileName . '_1_4.png',
            $path . $captureFileName . '_1_5.png',
            $path . $captureFileName . '_1_6.png',
            $path . $captureFileName . '_1_7.png',
            $path . $captureFileName . '_1_8.png',
            $path . $captureFileName . '_1_9.png',
            $path . $captureFileName . '_1_10.png',
            $path . $captureFileName . '_1_11.png',
        ];

        $this->deleteImageFiles($targetCaptureFiles);

        $cap = $this->createCapabilities(WebDriverBrowserType::IE);

        $driver = $this->createDriver($cap);
        $driver->get('https://www.google.com/search?gl=us&hl=en&gws_rd=cr&q=hello');

        // セレクター
        $selector  = '#nav > tbody';
        $selector2 = '#nav > tbody > tr > td';

        // 要素のセレクターを定義して
        $spec = new Spec($selector, Spec::EQUAL, 1);
        $spec2 = new Spec($selector2, Spec::GREATER_THAN, 10);

        // SpecPoolに突っ込む
        $specPool = (new SpecPool())
                    ->addSpec($spec)
                    ->addSpec($spec2);

        $actual = $this->target->takeElement($driver, $path, $captureFileName, $this->capabilities->getBrowserName(), $specPool, 1);

        $this->assertSame($targetCaptureFiles, $actual);
        foreach ($targetCaptureFiles as $file) {
            $this->assertFileExists($file);
        }

        $this->deleteImageFiles($targetCaptureFiles);
    }

    /*
     |--------------------------------------------------------------------------
     | helper
     |--------------------------------------------------------------------------
     */

    /**
     * 画像の削除
     * @param array $imageFiles
     */
    private function deleteImageFiles(array $imageFiles)
    {
        foreach ($imageFiles as $file) {
            if (file_exists($file)) {
                @unlink($file);
            }
        }
    }
}
