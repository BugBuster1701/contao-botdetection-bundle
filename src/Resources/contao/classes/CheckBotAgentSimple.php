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

/**
 * Class CheckBotAgentSimple 
 *
 * @copyright  Glen Langer 2015..2020 <http://contao.ninja>
 * @author     Glen Langer (BugBuster)
 */
class CheckBotAgentSimple
{
	/**
	 * Rough test - Definition
	 *
	 * @var array
	 */
	private	static $_BotsRough = array( 
							'analyzer',
							'archiver',
				            'bot', 
							'b o t',
							'cron',
							'checker',
							'fetcher',
							'scraper',
				            'spider', 
				            'spyder', 
							'crawl', 
							'reader',
				            'slurp',
							'robo',
							'validator',
				            //'yahoo',
					    	);

	/**
	 * Fine test - Definition
	 *
	 * @var array
	 */
	private static $_BotsFine = array( 
							'80legs', 
                            'abonti', // 3.2.0       	        
	                        'acoon', //1.6.0
					        'adressendeutschland',
                            'addressendeutschland', // 3.3.1
				            'agentname', 
				            'altavista', 
							'al_viewer',
							'ansible-httpget',
				            'appie', 
				            'appengine-google', //http://code.google.com/appengine
				            'arachnoidea', 
				            'archiver',
				            'asterias', 
							'ask jeeves',
							'barkrowler',	//1.6.0
				            'beholder', 
                            'bildsauger',   // 1.7.0
				            'bingsearch', 
	                        'bingpreview', // 1.6.2
	                        'blog search', // 3.3.1
	                        'bubing',      // 3.2.0
				            'bumblebee',
				            'bramptonmoose',
							'bbtest-net',	//Hobbit bbtest-net/4.2.0
							'cfnetwork',
							'check_http',	//check_mk monitoring-plugin
				            'cherrypicker', 
				            'crescent', 
	                        'coccoc', // 3.1.0
				            'cosmos/', 
				            'cronjob', // 3.3.4
				            'crowsnest', // 3.3.4
	                        'curl', // 1.6.2
				            'docomo',
                            'drupact', // 3.0.1
				            'emailsiphon', 
				            'emailwolf', 
				            'extractor', 
				            'exalead ng', 
				            'ezresult', 
				            'facebook',
				            'feedfetcher', //Feedfetcher-Google 
				            //'fido', 
				            'fireball', 
				            'flashget',
				            'flipboardproxy', // 3.0.1
				            'gazz', 
				            'genieo', // 3.2.0
				            'getright',
				            'getweb',
				            'gigabaz', 
				            'google talk', 
				            'google-site-verification', // Google Webmaster Tools
				            'google web preview',
				            'go!zilla',
				            'gozilla',
				            'gulliver', 
				            'harvester', 
				            'hcat', 
				            'heritrix',
				            'hloader', 
				            'hoge (',
				            'httrack',
				            'hubspot', // 3.2.0
				            'incywincy', 
				            'infoseek', 
				            'infohelfer',    // 3.0.1
				            'inktomi', 
				            'indy library', 
				            'informant', 
				            'internetami', 
				            'internetseer', 
				            'larbin', 
				            'libweb', 
							'libwww',
							'linkanalyze',
				            'jakarta', // 3.0.1
				            'java/1', // 1.6.2
				            'mata hari',
				            'mechanize', // 3.3.4 
				            'medicalmatrix', 
				            'mercator',
				            'metauri', // 3.3.4 
							'microsoft url control', //Harvester mit Spamflotte
							'microsoft office',
							'microsoft outlook',
				            'miixpc', 
				            'miva',    // 3.3.0
							'moget', 
							'monitoring',
				            'msnptc', 
				            'muscatferret', 
				            'netcraftsurveyagent',
				            'netants',
				            'ning/', // 3.3.4
				            'nutch',    // 3.3.0
				            'openxxx', 
							'pecl::http', // PECL::HTTP
							'petalbot',
							'phpservermon',
				            'picmole',
				            'pinterest', // 3.3.1
				            'pioneer internet',
				            'piranha', 
				            'pldi.net',
							'p357x',
							'prtg network monitor',
				            'publiclibraryarchive', // 3.3.4
				            'python-requests', // 3.3.4
				            'quosa', 
				            'rambler',		// russisch
                            'riddler', // 3.3.0
				            'rippers',
							'rganalytics',
							'ruby', // 3.3.4
				            'scan', 
				            'scooter', 
							'ScoutJet', 
				            'siclab', // 3.0.1
				            'siteexplorer', // 3.1.0
				            'sly', 
				            'suchen',
				            'searchme',
				            'snoopy',    // 1.6.2 
				            'spy', 
				            'swisssearch', 
							'sqworm', 
							'symfony browserkit',
							'symfony2 browserkit',
							'transcoder',
				            'trivial', 
				            't-h-u-n-d-e-r-s-t-o-n-e', 
				            'teoma', 
				            'twiceler',
							'ultraseek', 
							'uptime',
				            'validator',
				            'webbandit',
				            'webmastercoffee',
				            'website extractor',
				            'webwhacker',
				            'wevika', //1.6.0
				            'wget',
				            'wisewire', 
				            'wordpress', //1.6.1
				            'yandex',		// russisch
				            'zend_http_client', // 1.6.2
				            'whitehat',    // 3.3.0
							'Zabbix',
				            'zyborg'
				            ); 

    /**
     * checkAgent 
     * 
     * @param  string  $UserAgent
     * @return boolean
     */
    public static function checkAgent($UserAgent=false)
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

        // Rough search
        $CheckUserAgent = str_ireplace(static::$_BotsRough, '#', $UserAgent);
        if ($UserAgent != $CheckUserAgent)
        { 
            // found
            return true;
        }

        // Fine search
        $CheckUserAgent = str_ireplace(static::$_BotsFine, '#', $UserAgent);
        if ($UserAgent != $CheckUserAgent)
        { 
            // found
            return true;
        }

        // Feature #76, search for user bot filter definitions in localconfig.php
        if (isset($GLOBALS['BOTDETECTION']['BOT_AGENT']))
        {
            $botagents = array();
            foreach ($GLOBALS['BOTDETECTION']['BOT_AGENT'] as $search)
            {
                $botagents[$search[0]] = $search[1];
            }
            $num = \count($botagents);
            $arrBots = array_keys($botagents);
            for ($c=0; $c < $num; $c++)
            {
                $CheckUserAgent = str_ireplace($arrBots[$c], '#', $UserAgent);
                if ($UserAgent != $CheckUserAgent)
                {   
                    // found
                    return true;
                }
            }
        }

        return false;
    }//checkAgent
}
