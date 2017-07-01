<?php
/**
 * Contao Open Source CMS, Copyright (C) 2005-2017 Leo Feyer
 *
 * BotDetection - Blocker
 *
 * @copyright  Glen Langer 2007..2017 <http://contao.ninja>
 * @author     Glen Langer (BugBuster)
 * @package    BotDetection
 * @license    LGPL
 * @filesource
 * @see        https://github.com/BugBuster1701/contao-botdetection-bundle
 */

namespace BugBuster\BotDetection\Referrer;

/**
 * Class Blocker
 *
 * @copyright  Glen Langer 2017 <http://contao.ninja>
 * @author     Glen Langer (BugBuster)
 * @package    BotDetection  
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
     * 
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
     * 
     * @param string $blocklist false or "|domain1.de|domain2.com|...|"
     * @return boolean
     */
    public function isReferrerSpam($blocklist = false)
    {
        if (false === $blocklist) 
        {
            $referrerlist = $this->getCachePath() .'/referrerblocked.txt';
            $blocklist    = file_get_contents($referrerlist);
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

