<?php

/*
 * This file is part of a BugBuster Contao Bundle (Resources\contao)
 *
 * @copyright  Glen Langer 2024 <http://contao.ninja>
 * @author     Glen Langer (BugBuster)
 * @package    Contao BotDetection Bundle
 * @license    LGPL-3.0-or-later
 * @see        https://github.com/BugBuster1701/contao-botdetection-bundle
 */

namespace BugBuster\BotDetection\Referrer;

/**
 * Class Blocker
 *
 * @copyright  Glen Langer 2020 <http://contao.ninja>
 * @author     Glen Langer (BugBuster)
 */
class Blocker
{
    /**
     * Cache Path: absolute path 
     *
     * @var string
     */
    private $cachePath;

    private $referrer;

    /**
     * @param string $referrer  Referrer Domain
     * @param string $cachePath
     */
    public function __construct($referrer = false, $cachePath = false)
    {
        $this->setCachePath($cachePath);
        $this->setReferrer($referrer);
    }

    /**
     * @return the $cachePath
     */
    public function getCachePath()
    {
        return $this->cachePath;
    }

    /**
     * @return the $referrer
     */
    public function getReferrer()
    {
        return $this->referrer;
    }

    /**
     * @param string $cachePath
     */
    public function setCachePath($cachePath)
    {
        $this->cachePath = $cachePath;
    }

    /**
     * @param string $referrer
     */
    public function setReferrer($referrer)
    {
        $this->referrer = $referrer;
    }

    /**
     * @param  string  $blocklist false or "|domain1.de|domain2.com|...|"
     * @return boolean
     */
    public function isReferrerSpam($blocklist = false)
    {
        if (false === $blocklist) 
        {
            $referrerlist = $this->getCachePath() .'/referrerblocked.txt';
            $blocklist    = @file_get_contents($referrerlist) ?: 'Failed to open stream';
        }

        if (substr_count($blocklist, '|'. $this->getReferrer() .'|')) 
        {
            unset($blocklist);

            return true;
        }
        unset($blocklist);

        return false;
    }

}

