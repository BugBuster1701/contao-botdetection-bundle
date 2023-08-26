<?php

declare(strict_types=1);

/*
 * This file is part of a BugBuster Contao Bundle (Resources\contao)
 *
 * @copyright  Glen Langer 2023 <http://contao.ninja>
 * @author     Glen Langer (BugBuster)
 * @package    Contao BotDetection Bundle
 * @license    LGPL-3.0-or-later
 * @see        https://github.com/BugBuster1701/contao-botdetection-bundle
 */

/**
 * Run in a custom namespace
 */

namespace BugBuster\BotDetection;

use BugBuster\BotDetection\Referrer\ProviderCommunication;
use BugBuster\BotDetection\Referrer\ProviderParser;
use Contao\System;

/**
 * Class ModuleBotDetection
 *
 * @copyright  Glen Langer 2007..2020 <http://contao.ninja>
 * @author     Glen Langer (BugBuster)
 */
class ModuleBotDetection extends System
{

    /**
     * Current version of the class.
     */
    const BOTDETECTION_VERSION  = '1.8.2';

    const BOT_REFERRER_LIST     = "/vendor/bugbuster/contao-botdetection-bundle/src/Resources/contao/config/bot-referrer-list.php";
    const BOT_REFERRER_PROVIDER = "/vendor/bugbuster/contao-botdetection-bundle/src/Resources/contao/config/referrer-provider.php";

    const BOT_IP4_LIST          = "/vendor/bugbuster/contao-botdetection-bundle/src/Resources/contao/config/bot-ip-list-ipv4.txt";
    const BOT_IP6_LIST          = "/vendor/bugbuster/contao-botdetection-bundle/src/Resources/contao/config/bot-ip-list-ipv6.txt";

    /**
     * TL_ROOT over Container
     *
     * @var string
     */
    protected $rootDir;

    /**
     * Initialize object
     */
    public function __construct($rootDir = null)
    {
        parent::__construct();

        if (null === $rootDir) {
            $this->rootDir = System::getContainer()->getParameter('kernel.project_dir');
        }
        else {
            $this->rootDir = $rootDir;
        }
        $this->prefillCache();
        $this->deleteOldCache();

    }

    /**
     * Returns the version number
     *
     * @return string
     */
    public function getVersion(): string
    {
        return self::BOTDETECTION_VERSION;
    }

    /**
     * Spider Bot Agent/Advanced/Referrer/IP Check
     *
     * @param string   UserAgent, optional for tests
     * @return boolean true when bot found
     */
    public function checkBotAllTests($UserAgent = false): bool
    {
        $objUserAgent = new UserAgent($UserAgent);
        if (false === $UserAgent) 
        {            
        	$UserAgent = $objUserAgent->getUserAgent();
        }
        else
        {
            $UserAgent = $objUserAgent->setUserAgent($UserAgent);
        }
        if (CheckBotAgentSimple::checkAgent($UserAgent) === true) //(BotsRough, BotsFine)
        {
            return true;
        }

        if (true === (bool) CheckBotReferrer::checkReferrer(
            false,
            $this->rootDir . self::BOT_REFERRER_LIST,
            $this->rootDir . self::BOT_REFERRER_PROVIDER
        ) 
           )
        {
            return true;
        }

        if (false === $this->checkGetPostRequest()) // #153
        {
        	return true;
        }

        CheckBotIp::setBotIpv4List($this->rootDir . self::BOT_IP4_LIST);
        CheckBotIp::setBotIpv6List($this->rootDir . self::BOT_IP6_LIST);

        if (true === CheckBotIp::checkIP())
        {
            return true;
        }

        //CheckBotAgentExtended (Browscap + eigene Liste)
        return CheckBotAgentExtended::checkAgent($UserAgent);
    }

    /**
     * Check if Request a GET/POST Request
     * 
     * @return boolean true when GET/POST
     */
    public function checkGetPostRequest(): bool
    {
        $RequestMethod = \Contao\Environment::get('requestMethod');
        if ($RequestMethod == 'GET' || $RequestMethod == 'POST') 
        {
        	return true;
        }

        return false;
    }

    private function prefillCache()
    {
        if (!is_dir($this->rootDir . '/vendor/bugbuster/contao-botdetection-bundle/src/Resources/contao/config'))
        {
            return; // call from IDE for tests
        }
        $referrerProvider = array();
        include_once($this->rootDir . '/vendor/bugbuster/contao-botdetection-bundle/src/Resources/contao/config/referrer-provider.php');
        $proCom = new ProviderCommunication($referrerProvider, false, $this->rootDir);
        $cachePath = false;

        if (true === $proCom->loadProviderFiles())
        {
            $cachePath = $proCom->getCachePath();
        }

        if (false !== $cachePath)
        {
            $proPar = new ProviderParser($referrerProvider, $cachePath);

            if (true === $proPar->isUpdateProviderListNecessary() &&
                true === $proPar->generateProviderList()
                )
            {
                $proPar->cleanProviderList();
                $proPar->writeProviderList();
            }
        }
    }

    private function deleteOldCache()
    {
        if (!is_dir($this->rootDir . '/vendor/bugbuster/contao-botdetection-bundle/src/Resources/contao/config'))
        {
            return; // call from IDE for tests
        }
        //Only once a month.
        //runonce replacement, until I know it better. (The package has modified files...., abort...)
        $day = (int) date('j');
        if (1 == $day)
        {
            $olddirs = array('largebrowscap_v6006_1.0.4', 
                             'largebrowscap_v6008_1.0.4', 
                             'largebrowscap_v6015_1.0.4', 
                             'largebrowscap_v6021_1.0.5', 
                             'largebrowscap_v6026_1.0.5', 
                             'largebrowscap_v6030_1.0.5',
                             'largebrowscap_v6036_1.0.5',
                             'largebrowscap_v6040_1.0.5',
                             'largebrowscap_v6040_1.1.0'
                        );
            foreach ($olddirs as $olddir)
            {
                if (is_dir($this->rootDir . '/vendor/bugbuster/contao-botdetection-bundle/src/Resources/contao/cache/'.$olddir))
                {
                    $folder = new \Contao\Folder('vendor/bugbuster/contao-botdetection-bundle/src/Resources/contao/cache/'.$olddir);
                    if (!$folder->isEmpty())
                    {
                        $folder->purge();
                    }
                    $folder->delete();
                    $folder=null;
                    unset($folder);
                }
            }
        }
    }
}
