<?php

namespace SMB\Screru\Wrapper;

use Facebook\WebDriver\Remote\RemoteWebDriver as FacebookRemoteWebDriver;

/**
 * Wrapper of RemoteWebDriver
 */
class RemoteWebDriver extends FacebookRemoteWebDriver
{
    /**
     * 既にquitされているか
     * @var boolean
     */
    private $isQuit = false;

    /**
     * 既にcloseされているか
     * @var boolean
     */
    private $isClosed = false;

    /**
     * {@inheritdoc}
     */
    public function quit()
    {
        $this->isQuit = true;

        return parent::quit();
    }

    /**
     * {@inheritdoc}
     */
    public function close()
    {
        $this->isClosed = true;

        return parent::close();
    }

    /**
     * 既にquit()されているかを返す
     * @return boolean
     */
    public function isQuit()
    {
        return $this->isQuit;
    }

    /**
     * 既にclose()されているかを返す
     * @return boolean
     */
    public function isClosed()
    {
        return $this->isClosed;
    }
}
