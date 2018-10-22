<?php

declare(strict_types=1);

/**
 * Contao Open Source CMS, Copyright (C) 2005-2018 Leo Feyer
 *
 * Modul BotDetection - Frontend
 *
 * @copyright  Glen Langer 2007..2018 <http://contao.ninja>
 * @author     Glen Langer (BugBuster)
 * @license    LGPL
 * @filesource
 * @see        https://github.com/BugBuster1701/contao-botdetection-bundle
 */

/**
 * Run in a custom namespace
 */

namespace BugBuster\BotDetection;

use Contao\System;

/**
 * Class ModuleBotDetection
 *
 * @copyright  Glen Langer 2007..2018 <http://contao.ninja>
 * @author     Glen Langer (BugBuster)
 */
class ModuleBotDetection extends System
{

    /**
     * Current version of the class.
     */
    const BOTDETECTION_VERSION  = '1.5.0';

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
        if (false === $UserAgent) 
        {
        	$UserAgent = \Environment::get('httpUserAgent');
        }
        if (CheckBotAgentSimple::checkAgent($UserAgent) === true) //(BotsRough, BotsFine)
        {
            return true;
        }

        if (true === (bool) CheckBotReferrer::checkReferrer(false, 
                                                             $this->rootDir . self::BOT_REFERRER_LIST,
                                                             $this->rootDir . self::BOT_REFERRER_PROVIDER) 
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
        $RequestMethod = \Environment::get('requestMethod');
        if ($RequestMethod == 'GET' || $RequestMethod == 'POST') 
        {
        	return true;
        }

        return false;
    }

}
