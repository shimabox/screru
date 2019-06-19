<?php

namespace SMB\Screru\Tests\View;

use SMB\Screru\Screenshot\Screenshot;
use SMB\Screru\Elements\Spec;
use SMB\Screru\Elements\SpecPool;
use SMB\Screru\View\Observer;

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

    /**
     * PC:Chrome 画面スクロール時に通知がおこなわれる
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
    public function it_can_notification_of_pc_chrome()
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );

        putenv('ENABLED_CHROME_HEADLESS=');

        $cap = $this->createCapabilities(WebDriverBrowserType::CHROME);
        $dimension = $this->createDimension(['w' => 500, 'h' => 500]);

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
        $captureFileName = 'chrome.png';
        $targetCaptureFiles = [
            $path . $captureFileName,
        ];

        $screenshot = new Screenshot();
        $screenshot->setObserver($observerMock);
        $screenshot->takeFull($driver, $path, $captureFileName);

        $this->deleteImageFiles($targetCaptureFiles);
    }

    /**
     * Undocumented function
     *
     * @test
     * @group chrome
     */
    public function it_can_notification_of_pc_chrome_2()
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );

        putenv('ENABLED_CHROME_HEADLESS=');

        $cap = $this->createCapabilities(WebDriverBrowserType::CHROME);
        $dimension = $this->createDimension(['w' => 500, 'h' => 800]);

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
        $captureFileName = 'chrome.png';
        $targetCaptureFiles = [
            $path . $captureFileName,
        ];

        $screenshot = new Screenshot();
        $screenshot->setObserver($observerMock);
        $screenshot->takeFull($driver, $path, $captureFileName);

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
