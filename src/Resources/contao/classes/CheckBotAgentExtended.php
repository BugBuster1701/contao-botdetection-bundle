<?php

/*
 * This file is part of a BugBuster Contao Bundle (Resources\contao)
 *
 * @copyright  Glen Langer 2020 <http://contao.ninja>
 * @author     Glen Langer (BugBuster)
 * @package    Contao BotDetection Bundle
 * @license    LGPL-3.0-or-later
 * @see        https://github.com/BugBuster1701/contao-botdetection-bundle
 */

namespace BugBuster\BotDetection;

use Jaybizzle\CrawlerDetect\CrawlerDetect;

/**
 * Class CheckBotAgentExtended 
 *
 * @copyright  Glen Langer 2015..2020 <http://contao.ninja>
 * @author     Glen Langer (BugBuster)
 */
class CheckBotAgentExtended
{
    /**
     * checkAgent
     * 
     * @param  string         $UserAgent
     * @param  string         $ouputBotName
     * @return boolean|string
     */
    public static function checkAgent($UserAgent=false, $ouputBotName = false)
    {
        // Check if user agent present
        if ($UserAgent === false)
        {
            return false; // No user agent, no search.
        }

        $UserAgent = trim($UserAgent);
        if (false === (bool) $UserAgent)
        {
            return false; // No user agent, no search.
        }

        //Search in browsecap
        $objBrowscap = static::getBrowscapInfo($UserAgent);
        // DEBUG fwrite(STDOUT, 'BrowscapInfo: '.print_r($objBrowscap,true) . "\n");
        if ($objBrowscap->crawler == 'true') 
        {
            if (false === $ouputBotName) 
            {
            	return true;
            }
            else 
            {
                // DEBUG fwrite(STDOUT, "\n" . 'Agent: '.print_r($UserAgent,true) . "\n");
                // DEBUG fwrite(STDOUT, 'Bot: '.print_r($objBrowscap->browser_type,true) . "\n");
                return $objBrowscap->browser;
            }

        }

        // Search in bot-agent-list
        if (file_exists(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'config/bot-agent-list.php'))
        {
            include(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'config/bot-agent-list.php');
        }
        else
        {
            return false;	// no definition, no search
        }
        // search for user bot filter definitions in localconfig.php
        if (isset($GLOBALS['BOTDETECTION']['BOT_AGENT']))
        {
            foreach ($GLOBALS['BOTDETECTION']['BOT_AGENT'] as $search)
            {
                $botagents[$search[0]] = $search[1];
            }
        }
        $num = \count($botagents);
        $arrBots = array_keys($botagents);
        for ($c=0; $c < $num; $c++)
        {
            $CheckUserAgent = str_ireplace($arrBots[$c], '#', $UserAgent);
            if ($UserAgent != $CheckUserAgent)
            {   // found
                // Debug fwrite(STDOUT, 'Bot: '.print_r($botagents[$arrBots[$c]],true) . "\n");
                if (false === $ouputBotName)
                {
                    return true;
                }
                else 
                {
                    return $botagents[$arrBots[$c]];
                }
            }
        }

        $resultCrawlerDetect = static::checkAgentViaCrawlerDetect($UserAgent, $ouputBotName);
        if (false !== $resultCrawlerDetect)
        {
            return $resultCrawlerDetect;
        }

        return false;
    }

    public static function checkAgentName($UserAgent=false)
    {
        $BotName = static::checkAgent($UserAgent, true);

        return ($BotName) ? $BotName : false;
    }

    /**
     * getBrowscapResult for Debug
     */
    public static function getBrowscapResult($UserAgent=false)
    {
        return static::getBrowscapInfo($UserAgent);
    }

    /**
     * Get Browscap info for the user agent string
     * 
     * @param  string   $UserAgent
     * @return stdClass Object
     */
    protected static function getBrowscapInfo($UserAgent=false)
    {
        // Check if user agent present
        if ($UserAgent === false)
        {
            return false; // No user agent, no search.
        }

        // set an own cache directory (otherwise the system temp directory is used)
        //\Crossjoin\Browscap\Cache\File::setCacheDirectory(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'cache');
        \BugBuster\Browscap\Cache\File::setCacheDirectory(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'cache');
        //DEBUG fwrite(STDOUT, 'getCacheDirectory: '.print_r(\BugBuster\Browscap\Cache\File::getCacheDirectory(),true) . "\n");

        //Large sonst fehlt Browser_Type / Crawler um Bots zu erkennen
        //\Crossjoin\Browscap\Browscap::setDatasetType(\Crossjoin\Browscap\Browscap::DATASET_TYPE_LARGE);
        \BugBuster\Browscap\Browscap::setDatasetType(\BugBuster\Browscap\Browscap::DATASET_TYPE_LARGE);
        //DEBUG fwrite(STDOUT, 'getDataSetType 3: '.print_r(\BugBuster\Browscap\Browscap::getDataSetType(),true) . "\n");

        // disable automatic updates 
        //$updater = new \Crossjoin\Browscap\Updater\None(); 
        $updater = new \BugBuster\Browscap\Updater\None(); 
        //\Crossjoin\Browscap\Browscap::setUpdater($updater);
        \BugBuster\Browscap\Browscap::setUpdater($updater);
        //DEBUG fwrite(STDOUT, 'getUpdater: '.print_r(\BugBuster\Browscap\Browscap::getUpdater(),true) . "\n");

        //$browscap = new \Crossjoin\Browscap\Browscap(false); //autoUpdate = false
        $browscap = new \BugBuster\Browscap\Browscap(false); //autoUpdate = false
        //DEBUG fwrite(STDOUT, 'browscap: '.print_r($browscap,true) . "\n");
        $browser = $browscap->getBrowser($UserAgent);
        //DEBUG fwrite(STDOUT, 'browser: '.print_r($browser,true) . "\n");
        $settings = $browser->getData();

        return $settings;
        /*
            stdClass Object
            (
                [browser_name_regex] => /^Yandex\/1\.01\.001 \(compatible; Win16; .*\)$/
                [browser_name_pattern] => Yandex/1.01.001 (compatible; Win16; *)
                [parent] => Yandex
                [comment] => Yandex
                [browser] => Yandex
                [browser_type] => Bot/Crawler
                [browser_maker] => Yandex
                [crawler] => true
                .....
            stdClass Object
            (
                [browser_name_regex] => /^Googlebot\-Image.*$/
                [browser_name_pattern] => Googlebot-Image*
                [parent] => Googlebot
                [browser] => Googlebot-Image
                [comment] => Googlebot
                [browser_type] => Bot/Crawler
                [browser_maker] => Google Inc
                [crawler] => true
                .....
         */
    }

    public static function checkAgentViaCrawlerDetect($UserAgent=null, $ouputBotName = false)
    {
        $CrawlerDetect = new CrawlerDetect();

        if($CrawlerDetect->isCrawler($UserAgent)) 
        {
            if (false === $ouputBotName)
            {
                return true;
            }
            else 
            {
                return $CrawlerDetect->getMatches();
            }
        }

        return false;

    }

}
