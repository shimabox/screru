<?php

namespace SMB\Screru\Factory;

use SMB\Screru\Exception\DisabledWebDriverException;
use SMB\Screru\Exception\NotSpecifiedWebDriverException;

use Facebook\WebDriver\Chrome;
use Facebook\WebDriver\Firefox;
use Facebook\WebDriver\WebDriverDimension;
use Facebook\WebDriver\Remote\DesiredCapabilities as FacebookDesiredCapabilities;
use Facebook\WebDriver\Remote\WebDriverBrowserType;

/**
 * Factory of DesiredCapabilities
 */
class DesiredCapabilities
{
    /**
     * DesiredCapabilities
     * @var \Facebook\WebDriver\Remote\DesiredCapabilities
     */
    private $capabilities;

    /**
     * ChromeOptions
     * @var \Facebook\WebDriver\Chrome\ChromeOptions
     */
    private $chromeOptions;

    /**
     * Default UserAgent
     * @var string default iOS 12.0
     */
    private $defaultUserAgent = 'Mozilla/5.0 (iPhone; CPU iPhone OS 12_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/12.0 Mobile/15E148 Safari/604.1';

    /**
     * browser name
     * @var type
     */
    private $browser = '';

    /**
     * コンストラクタ
     * @param string $browser
     * @throws \SMB\Screru\Exception\DisabledWebDriverException
     * @throws \SMB\Screru\Exception\NotSpecifiedWebDriverException
     */
    public function __construct($browser)
    {
        switch ($browser) {
            case WebDriverBrowserType::FIREFOX: // firefox
                if (getenv('ENABLED_FIREFOX_DRIVER') !== 'true') {
                    throw new DisabledWebDriverException('geckodriver is disabled.');
                }

                if (getenv('IS_PLATFORM_WINDOWS') === 'true' && getenv('FIREFOX_DRIVER_PATH') === '') {
                    throw new NotSpecifiedWebDriverException('geckodriver is not specified.');
                } elseif (getenv('FIREFOX_DRIVER_PATH') !== '') {
                    putenv('webdriver.gecko.driver=' . getenv('FIREFOX_DRIVER_PATH'));
                }

                $this->capabilities = FacebookDesiredCapabilities::firefox();

                if (getenv('ENABLED_FIREFOX_HEADLESS') === 'true') {
                    $this->capabilities->setCapability('moz:firefoxOptions' , [
                        'args' => '-headless'
                    ]);
                }

                $this->browser = $browser;
                break;
            case WebDriverBrowserType::IE: // internet explorer
                if (getenv('ENABLED_IE_DRIVER') !== 'true') {
                    throw new DisabledWebDriverException('iedriver is disabled.');
                }

                if (getenv('IS_PLATFORM_WINDOWS') === 'true' && getenv('IE_DRIVER_PATH') === '') {
                    throw new NotSpecifiedWebDriverException('iedriver is not specified.');
                } elseif (getenv('IE_DRIVER_PATH') !== '') {
                    putenv('webdriver.ie.driver=' . getenv('IE_DRIVER_PATH'));
                }

                $this->capabilities = FacebookDesiredCapabilities::internetExplorer();
                $this->browser = $browser;
                break;
            case WebDriverBrowserType::CHROME: // chrome
            default:
                if (getenv('ENABLED_CHROME_DRIVER') !== 'true') {
                    throw new DisabledWebDriverException('chromedriver is disabled.');
                }

                if (getenv('IS_PLATFORM_WINDOWS') === 'true' && getenv('CHROME_DRIVER_PATH') === '') {
                    throw new NotSpecifiedWebDriverException('chromedriver is not specified.');
                } elseif (getenv('CHROME_DRIVER_PATH') !== '') {
                    putenv('webdriver.chrome.driver=' . getenv('CHROME_DRIVER_PATH'));
                }

                $this->capabilities = FacebookDesiredCapabilities::chrome();
                $this->chromeOptions = new Chrome\ChromeOptions();

                if (getenv('ENABLED_CHROME_HEADLESS') === 'true') {
                    $this->chromeOptions->addArguments(['--headless']);
                    // unknown error: DevToolsActivePort file doesn't exist
                    // https://github.com/heroku/heroku-buildpack-google-chrome/issues/46
                    $this->chromeOptions->addArguments(['--no-sandbox']);
                    $this->chromeOptions->addArguments(['--disable-dev-shm-usage']);
                }

                $this->capabilities->setCapability(Chrome\ChromeOptions::CAPABILITY, $this->chromeOptions);
                $this->browser = WebDriverBrowserType::CHROME;
                break;
        }

        if (getenv('OVERRIDE_DEFAULT_USER_AGENT') !== '') {
            $this->defaultUserAgent = getenv('OVERRIDE_DEFAULT_USER_AGENT');
        }
    }

    /**
     * getter DesiredCapabilities
     * @return \Facebook\WebDriver\Remote\DesiredCapabilities
     */
    public function get()
    {
        return $this->capabilities;
    }

    /**
     * setting default UserAgent(iOS 10.3.2)
     */
    public function settingDefaultUserAgent()
    {
        $this->settingUserAgent($this->defaultUserAgent);
    }

    /**
     * setter UserAgent
     * @param string $ua
     */
    public function setUserAgent($ua)
    {
        $this->settingUserAgent($ua);
    }

    /**
     * Set window size in headless mode.
     * Currently it is only chrome.
     * @param \Facebook\WebDriver\WebDriverDimension $dimension
     */
    public function setWindowSizeInHeadless(WebDriverDimension $dimension)
    {
        if ($this->browser !== WebDriverBrowserType::CHROME) {
            return;
        }

        $w = $dimension->getWidth();
        $h = $dimension->getHeight();
        $this->chromeOptions->addArguments(["window-size={$w},{$h}"]);
    }

    /**
     * setting UserAgent
     * @param string $ua
     */
    protected function settingUserAgent($ua)
    {
        switch ($this->browser) {
            case WebDriverBrowserType::CHROME:
                $this->chromeOptions->addArguments(['--user-agent=' . $ua]);
                $this->capabilities->setCapability(Chrome\ChromeOptions::CAPABILITY, $this->chromeOptions);
                break;
            case WebDriverBrowserType::FIREFOX:
                $profile = new Firefox\FirefoxProfile();
                $profile->setPreference('general.useragent.override', $ua);
                $this->capabilities->setCapability(Firefox\FirefoxDriver::PROFILE, $profile);
            default :
                break;
        }
    }
}
