<?php

namespace SMB\Screru\Tests\View;

use SMB\Screru\Elements\Spec;
use SMB\Screru\Elements\SpecPool;
use SMB\Screru\Factory\DesiredCapabilities;
use SMB\Screru\Screenshot\Screenshot;
use SMB\Screru\View\Observer;

use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverDimension;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\WebDriverBrowserType;

/**
 * Test of SMB\Screru\View\Observer
 * 
 * @group Observer
 */
class ObserverTest extends \PHPUnit_Framework_TestCase
{
    use \SMB\Screru\Traits\Testable {
        setUp as protected traitSetUp;
        tearDown as protected traitTearDown;
    }

    /** @var int */
    private $count_of_times_processForFirstRender_was_called = 0;
    /** @var int */
    private $count_of_times_processForScreenScroll_was_called = 0;
    /** @var int */
    private $count_of_times_processForViewWidthHasReachedEndForFirst_was_called = 0;
    /** @var int */
    private $count_of_times_processForFirstVerticalScroll_was_called = 0;
    /** @var int */
    private $count_of_times_processForViewHeightHasReachedEndForFirst_was_called = 0;
    /** @var int */
    private $count_of_times_processForLastRender_was_called = 0;
    /** @var int */
    private $count_of_times_processForRenderComplete_was_called = 0;

    /**
     * setUp
     */
    protected function setUp()
    {
        parent::setUp();

        $this->count_of_times_processForFirstRender_was_called = 0;
        $this->count_of_times_processForScreenScroll_was_called = 0;
        $this->count_of_times_processForViewWidthHasReachedEndForFirst_was_called = 0;
        $this->count_of_times_processForFirstVerticalScroll_was_called = 0;
        $this->count_of_times_processForViewHeightHasReachedEndForFirst_was_called = 0;
        $this->count_of_times_processForLastRender_was_called = 0;
        $this->count_of_times_processForRenderComplete_was_called = 0;
    }

    /**
     * 画面スクロール時に通知がおこなわれる
     * 
     * |1.|2.|3.|<br>
     * |4.|2.|2.|<br>
     * |5.|2.|6.|<br>
     * 7.
     * 
     * 1. notifyFirstRender<br>
     * 2. notifyScreenScroll<br>
     * 3. notifyThatViewWidthHasReachedEndForFirst<br>
     * 4. notifyFirstVerticalScroll<br>
     * 5. notifyThatViewHeightHasReachedEndForFirst<br>
     * 6. notifyLastRender<br>
     * 7. notifyRenderComplete
     * 
     * @test
     * @group chrome
     */
    public function it_can_notification()
    {
        if (getenv('ENABLED_CHROME_HEADLESS') !== 'true') {
            $this->markTestSkipped('Not successful except for headless chrome.');
        }

        $cap = $this->createCapabilities(WebDriverBrowserType::CHROME);
        $dimension = $this->createDimension(['w' => 500, 'h' => 250]);

        $driver = $this->createDriver($cap, $dimension);
        $driver->get('http://localhost:' . getenv('LOCAL_PORT') . '/for_observer.php');

        $observer = new Observer();

        $observer->processForFirstRender(function($driver, $contentsWidth, $contentsHeight, $scrolledWidth, $scrolledHeight){
            $this->parameter_verification($driver, $contentsWidth, $contentsHeight, $scrolledWidth, $scrolledHeight);
            $this->count_of_times_processForFirstRender_was_called++;
        });
        $observer->processForScreenScroll(function($driver, $contentsWidth, $contentsHeight, $scrolledWidth, $scrolledHeight){
            $this->parameter_verification($driver, $contentsWidth, $contentsHeight, $scrolledWidth, $scrolledHeight);
            $this->count_of_times_processForScreenScroll_was_called++;
        });
        $observer->processForViewWidthHasReachedEndForFirst(function($driver, $contentsWidth, $contentsHeight, $scrolledWidth, $scrolledHeight){
            $this->parameter_verification($driver, $contentsWidth, $contentsHeight, $scrolledWidth, $scrolledHeight);
            $this->count_of_times_processForViewWidthHasReachedEndForFirst_was_called++;
        });
        $observer->processForFirstVerticalScroll(function($driver, $contentsWidth, $contentsHeight, $scrolledWidth, $scrolledHeight){
            $this->parameter_verification($driver, $contentsWidth, $contentsHeight, $scrolledWidth, $scrolledHeight);
            $this->count_of_times_processForFirstVerticalScroll_was_called++;
        });
        $observer->processForViewHeightHasReachedEndForFirst(function($driver, $contentsWidth, $contentsHeight, $scrolledWidth, $scrolledHeight){
            $this->parameter_verification($driver, $contentsWidth, $contentsHeight, $scrolledWidth, $scrolledHeight);
            $this->count_of_times_processForViewHeightHasReachedEndForFirst_was_called++;
        });
        $observer->processForLastRender(function($driver, $contentsWidth, $contentsHeight, $scrolledWidth, $scrolledHeight){
            $this->parameter_verification($driver, $contentsWidth, $contentsHeight, $scrolledWidth, $scrolledHeight);
            $this->count_of_times_processForLastRender_was_called++;
        });
        $observer->processForRenderComplete(function($driver, $contentsWidth, $contentsHeight, $scrolledWidth, $scrolledHeight){
            $this->parameter_verification($driver, $contentsWidth, $contentsHeight, $scrolledWidth, $scrolledHeight);
            $this->count_of_times_processForRenderComplete_was_called++;
        });

        $path = $this->capturePath();
        $captureFileName  =  'it_can_notification_' . microtime(true) . '.png';
        $captureFileName2 =  'it_can_notification2_' . microtime(true) . '.png';
        $targetCaptureFiles = [
            $path . $captureFileName,
            $path . $captureFileName2,
        ];

        $screenshot = new Screenshot();

        $screenshot->setObserver($observer);
        $screenshot->takeFull($driver, $path, $captureFileName);

        $this->assertTrue($this->count_of_times_processForFirstRender_was_called === 1);
        $this->assertTrue($this->count_of_times_processForScreenScroll_was_called >= 1);
        $this->assertTrue($this->count_of_times_processForViewWidthHasReachedEndForFirst_was_called === 1);
        $this->assertTrue($this->count_of_times_processForFirstVerticalScroll_was_called === 1);
        $this->assertTrue($this->count_of_times_processForViewHeightHasReachedEndForFirst_was_called === 1);
        $this->assertTrue($this->count_of_times_processForLastRender_was_called === 1);
        $this->assertTrue($this->count_of_times_processForRenderComplete_was_called === 1);

        // initialize.
        $this->count_of_times_processForFirstRender_was_called = 0;
        $this->count_of_times_processForScreenScroll_was_called = 0;
        $this->count_of_times_processForViewWidthHasReachedEndForFirst_was_called = 0;
        $this->count_of_times_processForFirstVerticalScroll_was_called = 0;
        $this->count_of_times_processForViewHeightHasReachedEndForFirst_was_called = 0;
        $this->count_of_times_processForLastRender_was_called = 0;
        $this->count_of_times_processForRenderComplete_was_called = 0;

        // clear observer.
        $observer->clearProcessForFirstRender();
        $observer->clearProcessForScreenScroll();
        $observer->clearProcessForViewWidthHasReachedEndForFirst();
        $observer->clearProcessForFirstVerticalScroll();
        $observer->clearProcessForViewHeightHasReachedEndForFirst();
        $observer->clearProcessForLastRender();
        $observer->clearProcessForRenderComplete();

        // retake.
        $screenshot->takeFull($driver, $path, $captureFileName2);

        $this->assertEquals($this->count_of_times_processForFirstRender_was_called, 0);
        $this->assertEquals($this->count_of_times_processForScreenScroll_was_called, 0);
        $this->assertEquals($this->count_of_times_processForViewWidthHasReachedEndForFirst_was_called, 0);
        $this->assertEquals($this->count_of_times_processForFirstVerticalScroll_was_called, 0);
        $this->assertEquals($this->count_of_times_processForViewHeightHasReachedEndForFirst_was_called, 0);
        $this->assertEquals($this->count_of_times_processForLastRender_was_called, 0);
        $this->assertEquals($this->count_of_times_processForRenderComplete_was_called, 0);

        $this->deleteImageFiles($targetCaptureFiles);
    }

    /**
     * PC:Chrome(headless) 画面スクロール時に通知がおこなわれる
     * 
     * |  view  |<br>
     * |1.|2.|3.|<br>
     * |4.|2.|6.|<br>
     * 7.
     * 
     * 1. notifyFirstRender<br>
     * 2. notifyScreenScroll<br>
     * 3. notifyThatViewWidthHasReachedEndForFirst<br>
     * 4. notifyFirstVerticalScroll<br>
     * 5. notifyThatViewHeightHasReachedEndForFirst<br>
     * 6. notifyLastRender<br>
     * 7. notifyRenderComplete
     * 
     * @test
     * @group chrome
     */
    public function it_can_notification_by_pc_headless_chrome()
    {
        if (getenv('ENABLED_CHROME_HEADLESS') !== 'true') {
            $this->markTestSkipped('Not successful except for headless chrome.');
        }

        $cap = $this->createCapabilities(WebDriverBrowserType::CHROME);
        $dimension = $this->createDimension(['w' => 500, 'h' => 500]);

        // execute test.
        $this->it_can_notification_by_pc_headless($cap, $dimension, 'it_can_notification_by_pc_headless_chrome');
    }

    /**
     * SP(UA偽装):Chrome(headless) 画面スクロール時に通知がおこなわれる
     * 
     * |view|<br>
     * |1.|<br>
     * |4.|<br>
     * |2.|<br>
     * |2.|<br>
     * |6.|<br>
     * 7.
     * 
     * 1. notifyFirstRender<br>
     * 2. notifyScreenScroll<br>
     * 3. notifyThatViewWidthHasReachedEndForFirst<br>
     * 4. notifyFirstVerticalScroll<br>
     * 5. notifyThatViewHeightHasReachedEndForFirst<br>
     * 6. notifyLastRender<br>
     * 7. notifyRenderComplete
     * 
     * @test
     * @group chrome
     */
    public function it_can_notification_by_sp_headless_chrome()
    {
        if (getenv('ENABLED_CHROME_HEADLESS') !== 'true') {
            $this->markTestSkipped('Not successful except for headless chrome.');
        }

        $cap = $this->createCapabilities(WebDriverBrowserType::CHROME);
        $cap->settingDefaultUserAgent();

        $dimension = $this->createDimension(['w' => 375, 'h' => 667]);

        // execute test.
        $this->it_can_notification_by_sp_headless($cap, $dimension, 'it_can_notification_by_sp_headless_chrome');
    }

    /**
     * SP(UA偽装):Chrome(headless) 要素のスタイルをコントロールできる
     * 
     * @test
     * @group chrome
     */
    public function it_can_control_the_style_of_the_element_by_sp_headless_chrome()
    {
        if (getenv('ENABLED_CHROME_HEADLESS') !== 'true') {
            $this->markTestSkipped('Not successful except for headless chrome.');
        }

        $cap = $this->createCapabilities(WebDriverBrowserType::CHROME);
        $cap->settingDefaultUserAgent();

        $dimension = $this->createDimension(['w' => 375, 'h' => 667]);

        // execute test.
        $this->it_can_control_the_style_of_the_element($cap, $dimension, 'it_can_control_the_style_of_the_element_by_sp_headless_chrome');
    }

    /**
     * PC:Chrome(not headless) 画面スクロール時に通知がおこなわれる
     * 
     * |  view  |<br>
     * |1.|2.|3.|<br>
     * |4.|2.|2.|<br>
     * |5.|2.|6.|<br>
     * 7.
     * 
     * 1. notifyFirstRender<br>
     * 2. notifyScreenScroll<br>
     * 3. notifyThatViewWidthHasReachedEndForFirst<br>
     * 4. notifyFirstVerticalScroll<br>
     * 5. notifyThatViewHeightHasReachedEndForFirst<br>
     * 6. notifyLastRender<br>
     * 7. notifyRenderComplete
     * 
     * @test
     * @group chrome
     */
    public function it_can_notification_by_pc_chrome()
    {
        if (getenv('ENABLED_CHROME_HEADLESS') === 'true') {
            $this->markTestSkipped('Headless chrome does not succeed.');
        }

        $cap = $this->createCapabilities(WebDriverBrowserType::CHROME);
        $dimension = $this->createDimension(['w' => 500, 'h' => 500]);

        // execute test.
        $this->it_can_notification_by_pc($cap, $dimension, 'it_can_notification_by_pc_chrome');
    }

    /**
     * SP(UA偽装):Chrome(not headless) 画面スクロール時に通知がおこなわれる
     * 
     * |view|<br>
     * |1.|<br>
     * |4.|<br>
     * |6.|<br>
     * 7.
     * 
     * 1. notifyFirstRender<br>
     * 2. notifyScreenScroll<br>
     * 3. notifyThatViewWidthHasReachedEndForFirst<br>
     * 4. notifyFirstVerticalScroll<br>
     * 5. notifyThatViewHeightHasReachedEndForFirst<br>
     * 6. notifyLastRender<br>
     * 7. notifyRenderComplete
     * 
     * @test
     * @group chrome
     */
    public function it_can_notification_by_sp_chrome()
    {
        if (getenv('ENABLED_CHROME_HEADLESS') === 'true') {
            $this->markTestSkipped('Headless chrome does not succeed.');
        }

        $cap = $this->createCapabilities(WebDriverBrowserType::CHROME);
        $cap->settingDefaultUserAgent();

        $dimension = $this->createDimension(['w' => 375, 'h' => 1000]);

        // execute test.
        $this->it_can_notification_by_sp($cap, $dimension, 'it_can_notification_by_sp_chrome');
    }

    /**
     * SP(UA偽装):Chrome(not headless) 要素のスタイルをコントロールできる
     * 
     * @test
     * @group chrome
     */
    public function it_can_control_the_style_of_the_element_by_sp_chrome()
    {
        if (getenv('ENABLED_CHROME_HEADLESS') === 'true') {
            $this->markTestSkipped('Headless chrome does not succeed.');
        }

        $cap = $this->createCapabilities(WebDriverBrowserType::CHROME);
        $cap->settingDefaultUserAgent();

        $dimension = $this->createDimension(['w' => 375, 'h' => 667]);

        // execute test.
        $this->it_can_control_the_style_of_the_element($cap, $dimension, 'it_can_control_the_style_of_the_element_by_sp_chrome');
    }

    /**
     * PC:Firefox(headless) 画面スクロール時に通知がおこなわれる
     * 
     * |  view  |<br>
     * |1.|2.|3.|<br>
     * |4.|2.|6.|<br>
     * 7.
     * 
     * 1. notifyFirstRender<br>
     * 2. notifyScreenScroll<br>
     * 3. notifyThatViewWidthHasReachedEndForFirst<br>
     * 4. notifyFirstVerticalScroll<br>
     * 5. notifyThatViewHeightHasReachedEndForFirst<br>
     * 6. notifyLastRender<br>
     * 7. notifyRenderComplete
     * 
     * @test
     * @group firefox
     */
    public function it_can_notification_by_pc_headless_firefox()
    {
        if (getenv('ENABLED_FIREFOX_HEADLESS') !== 'true') {
            $this->markTestSkipped('Not successful except for headless firefox.');
        }

        $cap = $this->createCapabilities(WebDriverBrowserType::FIREFOX);
        $dimension = $this->createDimension(['w' => 500, 'h' => 600]);

        // execute test.
        $this->it_can_notification_by_pc_headless($cap, $dimension, 'it_can_notification_by_pc_headless_firefox');
    }

    /**
     * SP(UA偽装):Firefox(headless) 画面スクロール時に通知がおこなわれる
     * 
     * |view|<br>
     * |1.|<br>
     * |4.|<br>
     * |2.|<br>
     * |2.|<br>
     * |6.|<br>
     * 7.
     * 
     * 1. notifyFirstRender<br>
     * 2. notifyScreenScroll<br>
     * 3. notifyThatViewWidthHasReachedEndForFirst<br>
     * 4. notifyFirstVerticalScroll<br>
     * 5. notifyThatViewHeightHasReachedEndForFirst<br>
     * 6. notifyLastRender<br>
     * 7. notifyRenderComplete
     * 
     * @test
     * @group firefox
     */
    public function it_can_notification_by_sp_headless_firefox()
    {
        if (getenv('ENABLED_FIREFOX_HEADLESS') !== 'true') {
            $this->markTestSkipped('Not successful except for headless firefox.');
        }

        $cap = $this->createCapabilities(WebDriverBrowserType::CHROME);
        $cap->settingDefaultUserAgent();

        $dimension = $this->createDimension(['w' => 375, 'h' => 667]);

        // execute test.
        $this->it_can_notification_by_sp_headless($cap, $dimension, 'it_can_notification_by_sp_headless_firefox');
    }

    /**
     * SP(UA偽装):Firefox(headless) 要素のスタイルをコントロールできる
     * 
     * @test
     * @group firefox
     */
    public function it_can_control_the_style_of_the_element_by_sp_headless_firefox()
    {
        if (getenv('ENABLED_FIREFOX_HEADLESS') !== 'true') {
            $this->markTestSkipped('Not successful except for headless firefox.');
        }

        $cap = $this->createCapabilities(WebDriverBrowserType::FIREFOX);
        $cap->settingDefaultUserAgent();

        $dimension = $this->createDimension(['w' => 375, 'h' => 667]);

        // execute test.
        $this->it_can_control_the_style_of_the_element($cap, $dimension, 'it_can_control_the_style_of_the_element_by_sp_headless_firefox');
    }

    /**
     * PC:Firefox(not headless) 画面スクロール時に通知がおこなわれる
     * 
     * |  view  |<br>
     * |1.|2.|3.|<br>
     * |4.|2.|2.|<br>
     * |5.|2.|6.|<br>
     * 7.
     * 
     * 1. notifyFirstRender<br>
     * 2. notifyScreenScroll<br>
     * 3. notifyThatViewWidthHasReachedEndForFirst<br>
     * 4. notifyFirstVerticalScroll<br>
     * 5. notifyThatViewHeightHasReachedEndForFirst<br>
     * 6. notifyLastRender<br>
     * 7. notifyRenderComplete
     * 
     * @test
     * @group firefox
     */
    public function it_can_notification_by_pc_firefox()
    {
        if (getenv('ENABLED_FIREFOX_HEADLESS') === 'true') {
            $this->markTestSkipped('Headless firefox does not succeed.');
        }

        $cap = $this->createCapabilities(WebDriverBrowserType::FIREFOX);
        $dimension = $this->createDimension(['w' => 500, 'h' => 500]);

        // execute test.
        $this->it_can_notification_by_pc($cap, $dimension, 'it_can_notification_by_pc_firefox');
    }

    /**
     * SP(UA偽装):Firefox(not headless) 画面スクロール時に通知がおこなわれる
     * 
     * |view|<br>
     * |1.|<br>
     * |4.|<br>
     * |6.|<br>
     * 7.
     * 
     * 1. notifyFirstRender<br>
     * 2. notifyScreenScroll<br>
     * 3. notifyThatViewWidthHasReachedEndForFirst<br>
     * 4. notifyFirstVerticalScroll<br>
     * 5. notifyThatViewHeightHasReachedEndForFirst<br>
     * 6. notifyLastRender<br>
     * 7. notifyRenderComplete
     * 
     * @test
     * @group firefox
     */
    public function it_can_notification_by_sp_firefox()
    {
        if (getenv('ENABLED_FIREFOX_HEADLESS') === 'true') {
            $this->markTestSkipped('Headless firefox does not succeed.');
        }

        $cap = $this->createCapabilities(WebDriverBrowserType::FIREFOX);
        $cap->settingDefaultUserAgent();

        $dimension = $this->createDimension(['w' => 375, 'h' => 1200]);

        // execute test.
        $this->it_can_notification_by_sp($cap, $dimension, 'it_can_notification_by_sp_firefox');
    }

    /**
     * SP(UA偽装):Firefox(not headless) 要素のスタイルをコントロールできる
     * 
     * @test
     * @group firefox
     */
    public function it_can_control_the_style_of_the_element_by_sp_firefox()
    {
        if (getenv('ENABLED_FIREFOX_HEADLESS') === 'true') {
            $this->markTestSkipped('Headless firefox does not succeed.');
        }

        $cap = $this->createCapabilities(WebDriverBrowserType::FIREFOX);
        $cap->settingDefaultUserAgent();

        $dimension = $this->createDimension(['w' => 375, 'h' => 667]);

        // execute test.
        $this->it_can_control_the_style_of_the_element($cap, $dimension, 'it_can_control_the_style_of_the_element_by_sp_firefox');
    }

    /**
     * PC:IE(not headless) 画面スクロール時に通知がおこなわれる
     * 
     * |  view  |<br>
     * |1.|2.|3.|<br>
     * |4.|2.|2.|<br>
     * |5.|2.|6.|<br>
     * 7.
     * 
     * 1. notifyFirstRender<br>
     * 2. notifyScreenScroll<br>
     * 3. notifyThatViewWidthHasReachedEndForFirst<br>
     * 4. notifyFirstVerticalScroll<br>
     * 5. notifyThatViewHeightHasReachedEndForFirst<br>
     * 6. notifyLastRender<br>
     * 7. notifyRenderComplete
     * 
     * @test
     * @group ie
     */
    public function it_can_notification_by_pc_ie()
    {
        $cap = $this->createCapabilities(WebDriverBrowserType::IE);
        $dimension = $this->createDimension(['w' => 500, 'h' => 400]);

        // execute test.
        $this->it_can_notification_by_pc($cap, $dimension, 'it_can_notification_by_pc_ie');
    }

    /**
     * parameter verification.
     * 
     * @param RemoteWebDriver $driver
     * @param int $contentsWidth
     * @param int $contentsHeight
     * @param int $scrolledWidth
     * @param int $scrolledHeight
     */
    private function parameter_verification($driver, $contentsWidth, $contentsHeight, $scrolledWidth, $scrolledHeight)
    {
        if (! $driver instanceof RemoteWebDriver) {
            $this->fail('$driver is not a RemoteWebDriver instance.');
        }

        if (($contentsWidth > 0) === false) {
            $this->fail('The value of $contentsWidth has not been taken.');
        }

        if (($contentsHeight > 0) === false) {
            $this->fail('The value of $contentsHeight has not been taken.');
        }

        if ( ($scrolledWidth >= 0) === false) {
            $this->fail('The value of $scrolledWidth has not been taken.');
        }

        if ( ($scrolledHeight >= 0) === false) {
            $this->fail('The value of $scrolledHeight has not been taken.');
        }

        $this->assertTrue(true);
    }

    /**
     * execute test.
     * 
     * @param DesiredCapabilities $cap
     * @param WebDriverDimension $dimension
     * @param string $fileName
     */
    private function it_can_notification_by_pc_headless(DesiredCapabilities $cap, WebDriverDimension $dimension, $fileName)
    {
        $driver = $this->createDriver($cap, $dimension);
        $driver->get('http://localhost:' . getenv('LOCAL_PORT') . '/for_observer.php');

        $observerMock = $this->getObserverMock();
        $observerMock
            ->expects($this->once())
            ->method('notifyFirstRender')
            ;
        $observerMock
            ->expects($this->exactly(2))
            ->method('notifyScreenScroll')
            ;
        $observerMock
            ->expects($this->once())
            ->method('notifyThatViewWidthHasReachedEndForFirst')
            ;
        $observerMock
            ->expects($this->once())
            ->method('notifyFirstVerticalScroll')
            ;
        $observerMock
            ->expects($this->never())
            ->method('notifyThatViewHeightHasReachedEndForFirst')
            ;
        $observerMock
            ->expects($this->once())
            ->method('notifyLastRender')
            ;
        $observerMock
            ->expects($this->once())
            ->method('notifyRenderComplete')
            ;

        $path = $this->capturePath();
        $captureFileName = $fileName . '_' . microtime(true) . '.png';
        $targetCaptureFiles = [
            $path . $captureFileName,
        ];

        $screenshot = new Screenshot();
        $screenshot->setObserver($observerMock);
        $screenshot->takeFull($driver, $path, $captureFileName);

        $this->deleteImageFiles($targetCaptureFiles);
    }

    /**
     * execute test.
     * 
     * @param DesiredCapabilities $cap
     * @param WebDriverDimension $dimension
     * @param string $fileName
     */
    private function it_can_notification_by_pc(DesiredCapabilities $cap, WebDriverDimension $dimension, $fileName)
    {
        $driver = $this->createDriver($cap, $dimension);
        $driver->get('http://localhost:' . getenv('LOCAL_PORT') . '/for_observer.php');

        $observerMock = $this->getObserverMock();
        $observerMock
            ->expects($this->once())
            ->method('notifyFirstRender')
            ;
        $observerMock
            ->expects($this->exactly(4))
            ->method('notifyScreenScroll')
            ;
        $observerMock
            ->expects($this->once())
            ->method('notifyThatViewWidthHasReachedEndForFirst')
            ;
        $observerMock
            ->expects($this->once())
            ->method('notifyFirstVerticalScroll')
            ;
        $observerMock
            ->expects($this->once())
            ->method('notifyThatViewHeightHasReachedEndForFirst')
            ;
        $observerMock
            ->expects($this->once())
            ->method('notifyLastRender')
            ;
        $observerMock
            ->expects($this->once())
            ->method('notifyRenderComplete')
            ;

        $path = $this->capturePath();
        $captureFileName = $fileName . '_' . microtime(true) . '.png';
        $targetCaptureFiles = [
            $path . $captureFileName,
        ];

        $screenshot = new Screenshot();
        $screenshot->setObserver($observerMock);
        $screenshot->takeFull($driver, $path, $captureFileName);

        $this->deleteImageFiles($targetCaptureFiles);
    }

    /**
     * execute test.
     * 
     * @param DesiredCapabilities $cap
     * @param WebDriverDimension $dimension
     * @param string $fileName
     */
    private function it_can_notification_by_sp_headless(DesiredCapabilities $cap, WebDriverDimension $dimension, $fileName)
    {
        $driver = $this->createDriver($cap, $dimension);
        $driver->get('http://localhost:' . getenv('LOCAL_PORT'));

        $observerMock = $this->getObserverMock();
        $observerMock
            ->expects($this->once())
            ->method('notifyFirstRender')
            ;
        $observerMock
            ->expects($this->exactly(2))
            ->method('notifyScreenScroll')
            ;
        $observerMock
            ->expects($this->never())
            ->method('notifyThatViewWidthHasReachedEndForFirst')
            ;
        $observerMock
            ->expects($this->once())
            ->method('notifyFirstVerticalScroll')
            ;
        $observerMock
            ->expects($this->never())
            ->method('notifyThatViewHeightHasReachedEndForFirst')
            ;
        $observerMock
            ->expects($this->once())
            ->method('notifyLastRender')
            ;
        $observerMock
            ->expects($this->once())
            ->method('notifyRenderComplete')
            ;

        $path = $this->capturePath();
        $captureFileName = $fileName . '_' . microtime(true) . '.png';
        $targetCaptureFiles = [
            $path . $captureFileName,
        ];

        $screenshot = new Screenshot();
        $screenshot->setObserver($observerMock);
        $screenshot->takeFull($driver, $path, $captureFileName);

        $this->deleteImageFiles($targetCaptureFiles);
    }

    /**
     * execute test.
     * 
     * @param DesiredCapabilities $cap
     * @param WebDriverDimension $dimension
     * @param string $fileName
     */
    private function it_can_notification_by_sp(DesiredCapabilities $cap, WebDriverDimension $dimension, $fileName)
    {
        $driver = $this->createDriver($cap, $dimension);
        $driver->get('http://localhost:' . getenv('LOCAL_PORT'));

        $observerMock = $this->getObserverMock();
        $observerMock
            ->expects($this->once())
            ->method('notifyFirstRender')
            ;
        $observerMock
            ->expects($this->never())
            ->method('notifyScreenScroll')
            ;
        $observerMock
            ->expects($this->never())
            ->method('notifyThatViewWidthHasReachedEndForFirst')
            ;
        $observerMock
            ->expects($this->once())
            ->method('notifyFirstVerticalScroll')
            ;
        $observerMock
            ->expects($this->never())
            ->method('notifyThatViewHeightHasReachedEndForFirst')
            ;
        $observerMock
            ->expects($this->once())
            ->method('notifyLastRender')
            ;
        $observerMock
            ->expects($this->once())
            ->method('notifyRenderComplete')
            ;

        $path = $this->capturePath();
        $captureFileName = $fileName . '_' . microtime(true) . '.png';
        $targetCaptureFiles = [
            $path . $captureFileName,
        ];

        $screenshot = new Screenshot();
        $screenshot->setObserver($observerMock);
        $screenshot->takeFull($driver, $path, $captureFileName);

        $this->deleteImageFiles($targetCaptureFiles);
    }

    /**
     * execute test.
     * 
     * @param DesiredCapabilities $cap
     * @param WebDriverDimension $dimension
     * @param string $fileName
     */
    private function it_can_control_the_style_of_the_element(DesiredCapabilities $cap, WebDriverDimension $dimension, $fileName)
    {
        $driver = $this->createDriver($cap, $dimension);
        $driver->get('http://localhost:' . getenv('LOCAL_PORT'));

        $driver->wait(10, 100)->until(
            WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::cssSelector('.navbar'))
        );

        $path = $this->capturePath();
        $captureFileName = $fileName . '_' . microtime(true);
        $targetCaptureFiles = [
            $path . $captureFileName . '.png',
            $path . $captureFileName . '_0_0.png',
        ];

        $screenshot = new Screenshot();

        $observer = new Observer;
        $observer->processForFirstRender(function($driver) {
            $elements = $driver->findElements(WebDriverBy::cssSelector('.navbar'));
            $this->assertCount(1, $elements);
            $this->assertTrue($elements[0]->isDisplayed());
        });
        $observer->processForFirstVerticalScroll(function($driver) {
            $driver->executeScript("document.querySelector('.navbar') ? document.querySelector('.navbar').style.display = 'none' : null;");
            $elem = $driver->findElements(WebDriverBy::cssSelector('.navbar'))[0];
            $this->assertFalse($elem->isDisplayed());
        });
        $observer->processForRenderComplete(function($driver) {
            $driver->executeScript("document.querySelector('.navbar') ? document.querySelector('.navbar').style.display = 'inherit' : null;");
            $elem = $driver->findElements(WebDriverBy::cssSelector('.navbar'))[0];
            $this->assertTrue($elem->isDisplayed());
        });

        $screenshot->setObserver($observer);
        $screenshot->takeFull($driver, $path, $captureFileName);

        $spec = new Spec('.navbar', Spec::EQUAL, 1);
        $specPool = (new SpecPool())->addSpec($spec);
        $screenshot->takeElement($driver, $path, $captureFileName, $specPool);

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

    /**
     * 
     * @param array $setMethods
     * 
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getObserverMock(array $setMethods=[
        'notifyFirstRender',
        'notifyScreenScroll',
        'notifyThatViewWidthHasReachedEndForFirst',
        'notifyFirstVerticalScroll',
        'notifyThatViewHeightHasReachedEndForFirst',
        'notifyLastRender',
        'notifyRenderComplete',
    ])
    {
        return $this->getMockBuilder('SMB\Screru\View\Observer')
             ->setMethods($setMethods)
             ->getMock();
    }
}
