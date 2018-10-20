<?php

/**
 * Contao Open Source CMS, Copyright (C) 2005-2017 Leo Feyer
 *
 * BotDetection
 *
 * @copyright  Glen Langer 2007..2017 <http://contao.ninja>
 * @author     Glen Langer (BugBuster)
 * @package    BotDetection
 * @license    LGPL
 * @filesource
 * @see        https://github.com/BugBuster1701/contao-botdetection-bundle
 */

namespace BugBuster\BotDetection;
use BugBuster\BotDetection\Referrer\ProviderCommunication;
use BugBuster\BotDetection\Referrer\ProviderParser;
use BugBuster\BotDetection\Referrer\Blocker;


/**
 * Class CheckBotReferrer 
 *
 * @copyright  Glen Langer 2015..2017 <http://contao.ninja>
 * @author     Glen Langer (BugBuster)
 * @package    BotDetection
 */
class CheckBotReferrer
{
 
    /**
     * checkReferrer
     * 
     * @param string $Referrer            Referrer of Request
     * @param string $Bot_Referrer_List   Bot Referrer List, absolute Path+Filename including TL_ROOT
     * @param array  $Bot_Provider_List   Referrer Provider List, Name + URL
     * @return boolean                    true: found, false: not found
     */
    public static function checkReferrer($Referrer = false, $Bot_Referrer_List = false, $Bot_Provider_List = false )
    {
        $checkOwn   = false;
        $checkLocal = false;
        $cachePath  = false;
        $rootDir    = \System::getContainer()->getParameter('kernel.project_dir');
        
        if (false !== $Referrer) 
        {
        	$_SERVER['HTTP_REFERER'] = $Referrer;
        }
        
        $referrer_DNS    = self::getReferrerDns($Referrer);
        if ('unknown' == $referrer_DNS) 
        {
            return false;  // ein Referrer du haben musst
        }
        //returns true when a blocked referrer is detected
        //zuerst die mitgelieferte Provider Liste durchsuchen
        $blocker = new Blocker($referrer_DNS, $rootDir . '/vendor/bugbuster/contao-botdetection-bundle/src/Resources/contao/config');
        if ($blocker->isReferrerSpam())
        {
            return true;
        }
        unset($blocker);

        //zweiter Versuch, aktuelle Liste generieren und darin suchen
        if (false !== $Bot_Provider_List) 
        {
            $referrerProvider = array();
            include_once($Bot_Provider_List);
            
            $proCom = new ProviderCommunication($referrerProvider, false);
            
            if (true === $proCom->loadProviderFiles()) 
            {
                $cachePath = $proCom->getCachePath();
            }
            if (false !== $cachePath) 
            {
                $proPar = new ProviderParser($referrerProvider, $cachePath);
                
                if ( true === $proPar->isUpdateProviderListNecessary() &&
                     true === $proPar->generateProviderList()
                   )
                {
                    $proPar->cleanProviderList();
                    $proPar->writeProviderList();
                }   
                $blocker = new Blocker($referrer_DNS, $cachePath);
                if ($blocker->isReferrerSpam()) 
                {
                    return true;
                }
                
            }
        }
        
        //dritter Versuch, eigene lokale Liste
        $botreferrerlist = false;
        $botreferrerlist = static::getReferrerOwnList($Bot_Referrer_List);
        
        if ($botreferrerlist !== false) 
        {
        	$checkOwn = static::checkReferrerList($botreferrerlist, $referrer_DNS);
        }

        
        //vierter Versuch (localconfig)
       	$botreferrerlist = static::getReferrerLocalList();
       	if ($botreferrerlist !== false)
       	{
       	    $checkLocal = static::checkReferrerList($botreferrerlist, $referrer_DNS);
       	}
       	
       	if ($checkOwn === true || $checkLocal === true) 
       	{
       		return true;
       	}
        return false;
    }
    
    
    /*  .__        ,        ,      .
        [__)._. _ -+- _  _.-+- _  _|
        |   [  (_) | (/,(_. | (/,(_]
     */
    
    /**
     * Get Referrer List, delivered with this extension
     * 
     * @param string $Bot_Referrer_List
     * @return boolean|array:   false: no list, array: Referrer List
     */
    protected static function getReferrerOwnList($Bot_Referrer_List = false)
    {
        if (false === $Bot_Referrer_List)
        {
            return false;
        }
        
        $found = false;
        $botreferrerlist = array();
        
        // include bot-referrer-list $botreferrerlist
        if (file_exists($Bot_Referrer_List))
        {
            include($Bot_Referrer_List);
        }
        else
        {
            return false;	// no definition, no search
        }
        return $botreferrerlist;
    }
    
    /**
     * Get Referrer List, self defined over localconfig
     *
     * @param string $Bot_Referrer_List
     * @return boolean|array:   false: no list, array: Referrer List
     */
    protected static function getReferrerLocalList()
    {
        $botreferrerlist = array();
        if (      isset($GLOBALS['BOTDETECTION']['BOT_REFERRER'])
            && is_array($GLOBALS['BOTDETECTION']['BOT_REFERRER']) 
           )
        {
            foreach ($GLOBALS['BOTDETECTION']['BOT_REFERRER'] as $search)
            {
                $botreferrerlist[] = $search;
            }
            return $botreferrerlist;
        }
        return false;
    }
    
    /**
     * Get Root Domain from Referrer
     * 
     * @param string $Referrer
     * @return boolean|string   false: no dns, string: Domain
     */
    protected static function getReferrerDns($Referrer = false)
    {
        if ($Referrer === false)
        {
            $http_referrer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'unknown' ;
        }
        else
        {
            $http_referrer = $Referrer;
        }
        //nur den host Anteil prüfen
        $referrer_DNS = parse_url( $http_referrer, PHP_URL_HOST );
        if ($referrer_DNS === NULL)
        {
            //try this...
            $referrer_DNS = @parse_url( 'http://'.$http_referrer, PHP_URL_HOST );
            if ($referrer_DNS === NULL ||
                $referrer_DNS === false)
            {
                //wtf...
                return false;
            }
        }
        return $referrer_DNS;
    }
    
    /**
     * Compare Referrer List With Referrer Domain
     *  
     * @param array $botreferrerlist
     * @param string $referrer_DNS
     * @return boolean      true: found, false: not found
     */
    protected static function checkReferrerList($botreferrerlist, $referrer_DNS)
    {
        //Prüfung
        foreach ($botreferrerlist as $botreferrer)
        {
            $CheckBotRef = str_ireplace($botreferrer, '#', $referrer_DNS);
            if ($referrer_DNS != $CheckBotRef)
            {
                return true;
            };
        }
        return false;
    }
    
}
