<?php

/*
 * This file is part of a BugBuster Contao Bundle (Resources\contao)
 *
 * @copyright  Glen Langer 2023 <http://contao.ninja>
 * @author     Glen Langer (BugBuster)
 * @package    Contao BotDetection Bundle
 * @license    LGPL-3.0-or-later
 * @see        https://github.com/BugBuster1701/contao-botdetection-bundle
 */

namespace BugBuster\BotDetection;

use Jaybizzle\CrawlerDetect\CrawlerDetect;
use Psr\Log\NullLogger;

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

        $cacheDir = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'cache';
        $fileCache = new \League\Flysystem\Local\LocalFilesystemAdapter($cacheDir);
        $filesystem = new \League\Flysystem\Filesystem($fileCache);
        $cache = new \MatthiasMullie\Scrapbook\Psr16\SimpleCache(
            new \MatthiasMullie\Scrapbook\Adapters\Flysystem($filesystem)
        );
        //$logger = new \Monolog\Logger('BDGEN');
        //$logger->pushHandler(new Monolog\Handler\StreamHandler(getcwd() . '/log/generate.log', \Monolog\Logger::INFO));
        //$logger = \Contao\System::getContainer()->get('monolog.logger.contao.files'); // tl_log
        //$logger = \Contao\System::getContainer()->get('monolog.logger.contao'); // var/logs/dev-datum.log im Debug Modus
        $logger = new NullLogger();
        $browscap_cache = new \BrowscapPHP\Cache\BrowscapCache($cache, $logger);
        if (NULL == $browscap_cache->getVersion())
        {
            $logger->info('Generiere Cache');
            $converter_cache = new \BrowscapPHP\Cache\BrowscapCache($cache, $logger);
            $converter = new \BrowscapPHP\Helper\Converter($logger, $converter_cache);
            $content = file_get_contents($cacheDir . '/bot_browscap.ini');
            $iniVersion = $converter->getIniVersion($content);
            $logger->info('Version INI ' . $iniVersion);
            $converter->storeVersion();
            $logger->debug('storeVersion Ende');
            $converter->convertString($content);
            $logger->debug('convertString Ende');
        }

        $logger->info('Version Cache ' . $browscap_cache->getVersion());
        unset($browscap_cache);
        $browscap = new \BrowscapPHP\Browscap($cache, $logger);

        //DEBUG fwrite(STDOUT, 'browscap: '.print_r($browscap,true) . "\n");
        $browser = $browscap->getBrowser($UserAgent);
        //DEBUG fwrite(STDOUT, 'browser: '.print_r($browser,true) . "\n");

        return $browser;
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
