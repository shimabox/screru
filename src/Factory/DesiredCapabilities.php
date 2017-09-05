<?php

namespace SMB\Screru\Factory;

use SMB\Screru\Exception\DisabledWebDriverException;
use SMB\Screru\Exception\NotExistsWebDriverException;

use Facebook\WebDriver\Remote\DesiredCapabilities as FacebookDesiredCapabilities;
use Facebook\WebDriver\Remote\WebDriverBrowserType;
use Facebook\WebDriver\Chrome;
use Facebook\WebDriver\Firefox;

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
     * Default UserAgent
     * @var string default iOS 10.3.2
     */
    private $defaultUserAgent = 'Mozilla/5.0 (iPhone; CPU iPhone OS 10_3_2 like Mac OS X) AppleWebKit/603.2.4 (KHTML. like Gecko) Version/10.0 Mobile/14F89 Safari/602.1';

    /**
     * browser name
     * @var type
     */
    private $browser = '';

    /**
     * コンストラクタ
     * @param string $browser
     * @throws \SMB\Screru\Exception\DisabledWebDriverException
     * @throws \SMB\Screru\Exception\NotExistsWebDriverException
     */
    public function __construct($browser)
    {
        switch ($browser) {
            case WebDriverBrowserType::CHROME: // chrome
                if (getenv('ENABLED_CHROME_DRIVER') !== 'true') {
                    throw new DisabledWebDriverException('Disabled chrome webdriver');
                }

                if (getenv('IS_PLATFORM_WINDOWS') === 'true' && getenv('CHROME_DRIVER_PATH') === '') {
                    throw new NotExistsWebDriverException('not exists chrome webdriver');
                } elseif (getenv('CHROME_DRIVER_PATH') !== '') {
                    putenv('webdriver.chrome.driver=' . getenv('CHROME_DRIVER_PATH'));
                }

                $this->capabilities = FacebookDesiredCapabilities::chrome();
                $this->browser = $browser;
                break;
            case WebDriverBrowserType::IE: // internet explorer
                if (getenv('ENABLED_IE_DRIVER') !== 'true') {
                    throw new DisabledWebDriverException('Disabled ie webdriver');
                }

                if (getenv('IS_PLATFORM_WINDOWS') === 'true' && getenv('IE_DRIVER_PATH') === '') {
                    throw new NotExistsWebDriverException('not exists ie webdriver');
                } elseif (getenv('IE_DRIVER_PATH') !== '') {
                    putenv('webdriver.ie.driver=' . getenv('IE_DRIVER_PATH'));
                }

                $this->capabilities = FacebookDesiredCapabilities::internetExplorer();
                $this->browser = $browser;
                break;
            case WebDriverBrowserType::FIREFOX: // firefox
            default :
                if (getenv('ENABLED_FIREFOX_DRIVER') !== 'true') {
                    throw new DisabledWebDriverException('Disabled firefox webdriver');
                }

                if (getenv('IS_PLATFORM_WINDOWS') === 'true' && getenv('FIREFOX_DRIVER_PATH') === '') {
                    throw new NotExistsWebDriverException('not exists firefox webdriver');
                } elseif (getenv('FIREFOX_DRIVER_PATH') !== '') {
                    putenv('webdriver.gecko.driver=' . getenv('FIREFOX_DRIVER_PATH'));
                }

                $this->capabilities = FacebookDesiredCapabilities::firefox();
                $this->browser = WebDriverBrowserType::FIREFOX;
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
     * setting UserAgent
     * @param string $ua
     */
    protected function settingUserAgent($ua)
    {
        switch ($this->browser) {
            case WebDriverBrowserType::CHROME:
                $options = new Chrome\ChromeOptions();
                $options->addArguments(['--user-agent=' . $ua]);
                $this->capabilities->setCapability(Chrome\ChromeOptions::CAPABILITY, $options);
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
