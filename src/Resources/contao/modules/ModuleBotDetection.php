<?php

/**
 * Contao Open Source CMS, Copyright (C) 2005-2017 Leo Feyer
 *
 * Modul BotDetection - Frontend
 *
 * @copyright  Glen Langer 2007..2017 <http://contao.ninja>
 * @author     Glen Langer (BugBuster)
 * @package    BotDetection
 * @license    LGPL
 * @filesource
 * @see        https://github.com/BugBuster1701/contao-botdetection-bundle
 */

/**
 * Run in a custom namespace
 */
namespace BugBuster\BotDetection;

use BugBuster\BotDetection\CheckBotAgentExtended;
use BugBuster\BotDetection\CheckBotAgentSimple;
use BugBuster\BotDetection\CheckBotReferrer;
use BugBuster\BotDetection\CheckBotIp;


/**
 * Class ModuleBotDetection
 *
 * @copyright  Glen Langer 2007..2017 <http://contao.ninja>
 * @author     Glen Langer (BugBuster)
 * @package    BotDetection
 */
class ModuleBotDetection extends \System 
{
    
    /**
     * Current version of the class.
     */
    const BOTDETECTION_VERSION  = '1.0.0';
    
    const BOT_REFERRER_LIST     = "/vendor/bugbuster/contao-botdetection-bundle/src/Resources/contao/config/bot-referrer-list.php";
    const BOT_REFERRER_PROVIDER = "/vendor/bugbuster/contao-botdetection-bundle/src/Resources/contao/config/referrer-provider.php";
    
    const BOT_IP4_LIST          = "/vendor/bugbuster/contao-botdetection-bundle/src/Resources/contao/config/bot-ip-list-ipv4.txt";
    const BOT_IP6_LIST          = "/vendor/bugbuster/contao-botdetection-bundle/src/Resources/contao/config/bot-ip-list-ipv6.txt";
    
    /**
     * Initialize object
     */
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Returns the version number
     *
     * @return string
     * @access public
     */
    public function getVersion()
    {
        return self::BOTDETECTION_VERSION;
    }
    
    
    
    /**
     * Spider Bot Agent/Advanced/Referrer/IP Check
     *
     * @param string   UserAgent, optional for tests
     * @return boolean true when bot found
     * @access public
     */
    public function checkBotAllTests($UserAgent = false)
    {
        if (false === $UserAgent) 
        {
        	$UserAgent = \Environment::get('httpUserAgent');
        }
        if ( CheckBotAgentSimple::checkAgent( $UserAgent ) === true ) //(BotsRough, BotsFine)
        {
            return true;
        }
        
        if ( true === (bool) CheckBotReferrer::checkReferrer(false, 
                                                             TL_ROOT . self::BOT_REFERRER_LIST, 
                                                             TL_ROOT . self::BOT_REFERRER_PROVIDER) 
           )
        {
            return true;
        }

        if ( false === $this->checkGetPostRequest() ) // #153
        {
        	return true;
        }
        
        CheckBotIp::setBotIpv4List(TL_ROOT . self::BOT_IP4_LIST);
        CheckBotIp::setBotIpv6List(TL_ROOT . self::BOT_IP6_LIST);
        
        if ( true === CheckBotIp::checkIP() )
        {
            return true;
        }
        else
        {
            //CheckBotAgentExtended (Browscap + eigene Liste)
            return CheckBotAgentExtended::checkAgent( $UserAgent );
        }
    }
    
    /**
     * Check if Request a GET/POST Request
     * 
     * @return boolean  true when GET/POST
     * @access public
     */
    public function checkGetPostRequest()
    {
        $RequestMethod = \Environment::get('requestMethod');
        if ($RequestMethod == 'GET' || $RequestMethod == 'POST') 
        {
        	return true;
        }
        
        return false;
    }
    
}
