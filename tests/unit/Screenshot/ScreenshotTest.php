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
        $captureFileName = 'it_can_take_fullscreen_of_pc_chrome_' . microtime(true) . '.png';
        $byTakeScreenshotFileName = 'it_can_take_a_capture_of_pc_chrome_by_takeScreenshot_' . microtime(true) . '.png';

        $targetCaptureFiles = [
            $path . $captureFileName,
            $path . $byTakeScreenshotFileName
        ];

        $this->deleteImageFiles($targetCaptureFiles);

        $cap = $this->createCapabilities(WebDriverBrowserType::CHROME);
        $dimension = $this->createDimension(['w' => 800, 'h' => 600]);

        $driver = $this->createDriver($cap, $dimension);
        $driver->get('http://localhost:' . getenv('LOCAL_PORT') . '/for_screenshot.php');

        // $driver->takeScreenshot()
        // WebDriverDimensionに指定したサイズを無視するようになった ※Mac/chrome のみ
        $byTakeScreenshot = $this->target->take($driver, $path . $byTakeScreenshotFileName);
        $this->assertFileExists($byTakeScreenshot);
        $this->assertSame($path . $byTakeScreenshotFileName, $byTakeScreenshot);

        // takeFull()
        $actual = $this->target->takeFull($driver, $path, $captureFileName, 1);
        $this->assertFileExists($actual);
        $this->assertSame($path . $captureFileName, $actual);

        // $driver->takeScreenshot() で撮れた画像サイズと比較する
        list($fullWidth, $fullHeight) = getimagesize($actual);
        list($byTakeScreenshotWidth, $byTakeScreenshotHeight) = getimagesize($byTakeScreenshot);

        // Mac/chrome のみ, $driver->takeScreenshot() で撮った場合に
        // WebDriverDimensionに指定したサイズを無視するようになった.
        // ($driver->takeScreenshot() では横幅がフルサイズになってしまっている)
        // そのため、フルサイズキャプチャのほうが$driver->takeScreenshot()で撮ったキャプチャより
        // 縦幅サイズが大きくなっているかで確認する.
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
        $captureFileName = 'it_can_take_fullscreen_of_forge_ua_sp_chrome_' . microtime(true);
        $byTakeScreenshotFileName = 'it_can_take_a_capture_of_forge_ua_sp_chrome_by_takeScreenshot_' . microtime(true);

        $targetCaptureFiles = [
            $path . $captureFileName . '.png',
            $path . $byTakeScreenshotFileName . '.png'
        ];

        $this->deleteImageFiles($targetCaptureFiles);

        $cap = $this->createCapabilities(WebDriverBrowserType::CHROME);
        $cap->settingDefaultUserAgent();

        $dimension = $this->createDimension(['w' => 375, 'h' => 667]);

        $driver = $this->createDriver($cap, $dimension);
        $driver->get('http://localhost:' . getenv('LOCAL_PORT') . '/for_screenshot.php');

        // $driver->takeScreenshot()
        // WebDriverDimensionに指定したサイズを無視するようになった ※Mac/chrome のみ
        $byTakeScreenshot = $this->target->take($driver, $path . $byTakeScreenshotFileName . '.jpg');
        $this->assertFileExists($byTakeScreenshot);
        $this->assertSame($path . $byTakeScreenshotFileName . '.png', $byTakeScreenshot);

        // takeFullScreenshot()
        $actual = $this->takeFullScreenshot($driver, $captureFileName . '.JPEG', 1);
        $this->assertFileExists($actual);
        $this->assertSame($path . $captureFileName . '.png', $actual);

        // $driver->takeScreenshot() で撮れた画像サイズと比較する
        list($fullWidth, $fullHeight) = getimagesize($actual);
        list($byTakeScreenshotWidth, $byTakeScreenshotHeight) = getimagesize($byTakeScreenshot);

        // Mac/chrome のみ, $driver->takeScreenshot() で撮った場合に
        // WebDriverDimensionに指定したサイズを無視するようになった.
        // ($driver->takeScreenshot() では横幅がフルサイズになってしまっている)
        // そのため、フルサイズキャプチャのほうが$driver->takeScreenshot()で撮ったキャプチャより
        // 縦幅サイズが大きくなっているかで確認する.
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
        $captureFileName = 'it_can_take_fullscreen_of_pc_firefox_' . microtime(true) . '.png';
        $byTakeScreenshotFileName = 'it_can_take_a_capture_of_pc_firefox_by_takeScreenshot_' . microtime(true) . '.png';

        $targetCaptureFiles = [
            $path . $captureFileName,
            $path . $byTakeScreenshotFileName
        ];

        $this->deleteImageFiles($targetCaptureFiles);

        $cap = $this->createCapabilities(WebDriverBrowserType::FIREFOX);
        $dimension = $this->createDimension(['w' => 800, 'h' => 600]);

        $driver = $this->createDriver($cap, $dimension);
        $driver->get('http://localhost:' . getenv('LOCAL_PORT') . '/for_screenshot.php');

        // $driver->takeScreenshot()
        $byTakeScreenshot = $this->target->take($driver, $path . $byTakeScreenshotFileName);
        $this->assertFileExists($byTakeScreenshot);
        $this->assertSame($path . $byTakeScreenshotFileName, $byTakeScreenshot);

        // takeFull()
        $actual = $this->target->takeFull($driver, $path, $captureFileName, 1);
        $this->assertFileExists($actual);
        $this->assertSame($path . $captureFileName, $actual);

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
        $captureFileName = 'it_can_take_fullscreen_of_forge_ua_sp_firefox_' . microtime(true) . '.png';
        $byTakeScreenshotFileName = 'it_can_take_a_capture_of_forge_ua_sp_firefox_by_takeScreenshot_' . microtime(true) . '.png';

        $targetCaptureFiles = [
            $path . $captureFileName,
            $path . $byTakeScreenshotFileName
        ];

        $this->deleteImageFiles($targetCaptureFiles);

        $cap = $this->createCapabilities(WebDriverBrowserType::FIREFOX);
        $cap->settingDefaultUserAgent();

        $dimension = $this->createDimension(['w' => 375, 'h' => 667]);

        $driver = $this->createDriver($cap, $dimension);
        $driver->get('http://localhost:' . getenv('LOCAL_PORT') . '/for_screenshot.php');

        // $driver->takeScreenshot()
        $byTakeScreenshot = $this->target->take($driver, $path . $byTakeScreenshotFileName);
        $this->assertFileExists($byTakeScreenshot);
        $this->assertSame($path . $byTakeScreenshotFileName, $byTakeScreenshot);

        // takeFullScreenshot()
        $actual = $this->takeFullScreenshot($driver, $captureFileName, 1);
        $this->assertFileExists($actual);
        $this->assertSame($path . $captureFileName, $actual);

        // $driver->takeScreenshot() で撮れた画像サイズと比較する
        list($fullWidth, $fullHeight) = getimagesize($actual);
        list($byTakeScreenshotWidth, $byTakeScreenshotHeight) = getimagesize($byTakeScreenshot);

        $this->assertTrue($fullWidth > $byTakeScreenshotWidth); // width は一緒のはず
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
        $captureFileName = 'it_can_take_fullscreen_of_pc_ie_' . microtime(true) . '.png';
        $byTakeScreenshotFileName = 'it_can_take_a_capture_of_pc_ie_by_takeScreenshot_' . microtime(true) . '.png';

        $targetCaptureFiles = [
            $path . $captureFileName,
            $path . $byTakeScreenshotFileName
        ];

        $this->deleteImageFiles($targetCaptureFiles);

        $cap = $this->createCapabilities(WebDriverBrowserType::IE);
        $dimension = $this->createDimension(['w' => 800, 'h' => 600]);

        $driver = $this->createDriver($cap, $dimension);
        $driver->get('http://localhost:' . getenv('LOCAL_PORT') . '/for_screenshot.php');

        // $driver->takeScreenshot()
        $byTakeScreenshot = $this->target->take($driver, $path . $byTakeScreenshotFileName);
        $this->assertFileExists($byTakeScreenshot);
        $this->assertSame($path . $byTakeScreenshotFileName, $byTakeScreenshot);

        // takeFull()
        $actual = $this->target->takeFull($driver, $path, $captureFileName, 1);
        $this->assertFileExists($actual);
        $this->assertSame($path . $captureFileName, $actual);

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
        $captureFileName = 'it_can_take_element_of_pc_chrome_' . microtime(true);
        $targetCaptureFiles = [
            $path . $captureFileName . '_0_0.png', // div.jumbotron > div > h1
            $path . $captureFileName . '_1_0.png', // div.container div.col-md-4
            $path . $captureFileName . '_1_1.png',
            $path . $captureFileName . '_1_2.png',
            $path . $captureFileName . '_1_3.png',
            $path . $captureFileName . '_1_4.png',
            $path . $captureFileName . '_1_5.png',
        ];

        $this->deleteImageFiles($targetCaptureFiles);

        $cap = $this->createCapabilities(WebDriverBrowserType::CHROME);

        $driver = $this->createDriver($cap);
        $driver->get('http://localhost:' . getenv('LOCAL_PORT') . '/for_screenshot.php');

        // セレクター
        $selector  = 'div.jumbotron > div > h1';
        $selector2 = 'div.container div.col-md-4';

        // 要素のセレクターを定義して
        $spec = new Spec($selector, Spec::EQUAL, 1);
        $spec2 = new Spec($selector2, Spec::EQUAL, 6);

        // SpecPoolに突っ込む
        $specPool = (new SpecPool())
                    ->addSpec($spec)
                    ->addSpec($spec2);

        $actual = $this->target->takeElement($driver, $path, $captureFileName . '.PNG', $specPool, 1);

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
        $captureFileName = 'it_can_take_element_of_forge_ua_sp_chrome_' . microtime(true);
        $targetCaptureFiles = [
            $path . $captureFileName . '_0_0.png', // div.jumbotron > div > h1
            $path . $captureFileName . '_1_0.png', // div.container div.col-md-4
            $path . $captureFileName . '_1_1.png',
            $path . $captureFileName . '_1_2.png',
            $path . $captureFileName . '_1_3.png',
            $path . $captureFileName . '_1_4.png',
            $path . $captureFileName . '_1_5.png',
            $path . $captureFileName . '_1_6.png',
            $path . $captureFileName . '_1_7.png',
            $path . $captureFileName . '_1_8.png',
        ];

        $this->deleteImageFiles($targetCaptureFiles);

        $cap = $this->createCapabilities(WebDriverBrowserType::CHROME);
        $cap->settingDefaultUserAgent();

        $dimension = $this->createDimension(['w' => 375, 'h' => 667]);

        $driver = $this->createDriver($cap, $dimension);
        $driver->get('http://localhost:' . getenv('LOCAL_PORT') . '/for_screenshot.php');

        // セレクター
        $selector  = 'div.jumbotron > div > h1';
        $selector2 = 'div.container div.col-md-4';

        // 要素のセレクターを定義して
        $spec = new Spec($selector, Spec::EQUAL, 1);
        $spec2 = new Spec($selector2, Spec::EQUAL, 9);

        // SpecPoolに突っ込む
        $specPool = (new SpecPool())
                    ->addSpec($spec)
                    ->addSpec($spec2);

        $actual = $this->takeElementScreenshot($driver, $captureFileName . '.JPG', $specPool);

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
        $captureFileName = 'it_can_take_element_of_pc_firefox_' . microtime(true);
        $targetCaptureFiles = [
            $path . $captureFileName . '_0_0.png', // div.jumbotron > div > h1
            $path . $captureFileName . '_1_0.png', // div.container div.col-md-4
            $path . $captureFileName . '_1_1.png',
            $path . $captureFileName . '_1_2.png',
            $path . $captureFileName . '_1_3.png',
            $path . $captureFileName . '_1_4.png',
            $path . $captureFileName . '_1_5.png',
        ];

        $this->deleteImageFiles($targetCaptureFiles);

        $cap = $this->createCapabilities(WebDriverBrowserType::FIREFOX);

        $driver = $this->createDriver($cap);
        $driver->get('http://localhost:' . getenv('LOCAL_PORT') . '/for_screenshot.php');

        // セレクター
        $selector  = 'div.jumbotron > div > h1';
        $selector2 = 'div.container div.col-md-4';

        // 要素のセレクターを定義して
        $spec = new Spec($selector, Spec::EQUAL, 1);
        $spec2 = new Spec($selector2, Spec::EQUAL, 6);

        // SpecPoolに突っ込む
        $specPool = (new SpecPool())
                    ->addSpec($spec)
                    ->addSpec($spec2);

        $actual = $this->target->takeElement($driver, $path, $captureFileName, $specPool, 1);

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
        $captureFileName = 'it_can_take_element_of_forge_ua_sp_firefox_' . microtime(true);
        $targetCaptureFiles = [
            $path . $captureFileName . '_0_0.png', // div.jumbotron > div > h1
            $path . $captureFileName . '_1_0.png', // div.container div.col-md-4
            $path . $captureFileName . '_1_1.png',
            $path . $captureFileName . '_1_2.png',
            $path . $captureFileName . '_1_3.png',
            $path . $captureFileName . '_1_4.png',
            $path . $captureFileName . '_1_5.png',
            $path . $captureFileName . '_1_6.png',
            $path . $captureFileName . '_1_7.png',
            $path . $captureFileName . '_1_8.png',
        ];

        $this->deleteImageFiles($targetCaptureFiles);

        $cap = $this->createCapabilities(WebDriverBrowserType::FIREFOX);
        $cap->settingDefaultUserAgent();

        $dimension = $this->createDimension(['w' => 375, 'h' => 667]);

        $driver = $this->createDriver($cap, $dimension);
        $driver->get('http://localhost:' . getenv('LOCAL_PORT') . '/for_screenshot.php');

        // セレクター
        $selector  = 'div.jumbotron > div > h1';
        $selector2 = 'div.container div.col-md-4';

        // 要素のセレクターを定義して
        $spec = new Spec($selector, Spec::EQUAL, 1);
        $spec2 = new Spec($selector2, Spec::EQUAL, 9);

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
        $captureFileName = 'it_can_take_element_of_pc_ie_' . microtime(true);
        $targetCaptureFiles = [
            $path . $captureFileName . '_0_0.png', // div.jumbotron > div > h1
            $path . $captureFileName . '_1_0.png', // div.container div.col-md-4
            $path . $captureFileName . '_1_1.png',
            $path . $captureFileName . '_1_2.png',
            $path . $captureFileName . '_1_3.png',
            $path . $captureFileName . '_1_4.png',
            $path . $captureFileName . '_1_5.png',
        ];

        $this->deleteImageFiles($targetCaptureFiles);

        $cap = $this->createCapabilities(WebDriverBrowserType::IE);

        $driver = $this->createDriver($cap);
        $driver->get('http://localhost:' . getenv('LOCAL_PORT') . '/for_screenshot.php');

        // セレクター
        $selector  = 'div.jumbotron > div > h1';
        $selector2 = 'div.container div.col-md-4';

        // 要素のセレクターを定義して
        $spec = new Spec($selector, Spec::EQUAL, 1);
        $spec2 = new Spec($selector2, Spec::EQUAL, 6);

        // SpecPoolに突っ込む
        $specPool = (new SpecPool())
                    ->addSpec($spec)
                    ->addSpec($spec2);

        $actual = $this->target->takeElement($driver, $path, $captureFileName, $specPool, 1);

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
