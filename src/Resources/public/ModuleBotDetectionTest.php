<?php 

/**
 * Contao Open Source CMS, Copyright (C) 2005-2017 Leo Feyer
 *
 * Modul BotDetection - Test
 *
 * @copyright  Glen Langer 2007..2017 <http://contao.ninja>
 * @author     Glen Langer (BugBuster)
 * @package    BotDetectionTest 
 * @license    LGPL 
 * @filesource
 * @see        https://github.com/BugBuster1701/contao-botdetection-bundle
 */

/**
 * Aufruf direkt!
 * http://deine-domain.de/bundles/bugbusterbotdetection/ModuleBotDetectionTest.php   
 * 
 */

/**
 * Initialize the system
 */
define('TL_SCRIPT', 'ModuleBotDetectionTest.php');
define('TL_MODE', 'FE');
$dir = __DIR__;
 
while ($dir != '.' && $dir != '/' && !is_file($dir . '/system/initialize.php'))
{
    $dir = dirname($dir);
}
 
if (!is_file($dir . '/system/initialize.php'))
{
    throw new \ErrorException('Could not find initialize.php!',2,1,basename(__FILE__),__LINE__);
}
require($dir . '/system/initialize.php');


/**
 * Class ModuleBotDetectionTest 
 *
 * @copyright  Glen Langer 2007..2017 <http://contao.ninja>
 * @author     Glen Langer (BugBuster)
 * @package    BotDetectionTest
 */
class ModuleBotDetectionTest extends \BugBuster\BotDetection\ModuleBotDetection  
{
    /**
     * Initialize object
     */
    public function __construct()
    {
        parent::__construct();
    }

	public function run()
	{
	    // AGENT TEST DEFINITIONS
	    
	    $arrTest[] = array(false, false,'your browser'); // own Browser
	    //Browser
	    $arrTest[] = array(false, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3','Firefox');
	    $arrTest[] = array(false, 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.2; Trident/4.0; Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1);','IE8.0');
	    $arrTest[] = array(false, 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; de; rv:1.9.2.2) Gecko/20100316 Firefox/3.6.2','Macintosh FF');
	    $arrTest[] = array(false, 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_3; de-de) AppleWebKit/531.22.7 (KHTML, like Gecko) Version/4.0.5 Safari/531.22.7','Macintosh Safari');
	    //Bots
	    $arrTest[] = array(true, 'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)','Google Bot');
	    $arrTest[] = array(true, 'ia_archiver (+http://www.alexa.com/site/help/webmasters; crawler@alexa.com)','Internet Archive');
	    $arrTest[] = array(true, 'Yandex/1.01.001 (compatible; Win16; P)','Yandex');
	    $arrTest[] = array(true, 'Mozilla/5.0 (compatible; Yahoo! Slurp; http://help.yahoo.com/help/us/ysearch/slurp)','Yahoo! Slurp');
	    $arrTest[] = array(true, 'Mozilla/5.0 (compatible; Exabot/3.0; +http://www.exabot.com/go/robot)','Exabot');
	    $arrTest[] = array(true, 'msnbot/2.0b (+http://search.msn.com/msnbot.htm)','msnbot');
	    $arrTest[] = array(true, 'Mozilla/5.0 (Twiceler-0.9 http://www.cuil.com/twiceler/robot.html)','Twiceler');
	    $arrTest[] = array(true, 'Googlebot-Image/1.0','Google Image Search');
	    $arrTest[] = array(true, 'Yeti/1.0 (NHN Corp.; http://help.naver.com/robots/)','NaverBot');
	    $arrTest[] = array(true, 'Baiduspider+(+http://www.baidu.com/search/spider.htm)','Baiduspider');
	    $arrTest[] = array(true, 'Mozilla/5.0 (compatible; spbot/2.0.2; +http://www.seoprofiler.com/bot/ )','SEOprofiler');
	    $arrTest[] = array(true, 'ia_archiver-web.archive.org','Internet Archive');
	    $arrTest[] = array(true, 'Mozilla/5.0 (compatible; archive.org_bot +http://www.archive.org/details/archive.org_bot)','Internet Archive');

	    $arrTest[] = array(true, 'msnbot-media/1.1 (+http://search.msn.com/msnbot.htm)','msnbot-media');
	    $arrTest[] = array(true, 'Mozilla/5.0 (compatible; Ask Jeeves/Teoma; +http://about.ask.com/en/docs/about/webmasters.shtml)','Teoma');
	    $arrTest[] = array(true, 'TrueKnowledgeBot (http://www.trueknowledge.com/tkbot/; tkbot -AT- trueknowledge _dot_ com)','TrueKnowledgeBot');
	    $arrTest[] = array(true, 'Mozilla/5.0 (compatible; ptd-crawler; +http://bixolabs.com/crawler/ptd/; crawler@bixolabs.com)','ptd-crawler bixolabs.com');
		$arrTest[] = array(true, 'Cityreview Robot (+http://www.cityreview.org/crawler/)','Cityreview Robot');
		$arrTest[] = array(true, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9) Gecko/2008052906 Firefox/3.0/1.0 (bot; http://)','No-Name-Bot');
		$arrTest[] = array(true, 'Mozilla/5.0 (en-us) AppleWebKit/525.13 (KHTML, like Gecko; Google Web Preview) Version/3.1 Safari/525.13','Google Web Preview');
		
		$arrTest[] = array(true, 'Mozilla/5.0 (compatible; Spiderlytics/1.0; +spider@spiderlytics.com)','Spiderlytics');
		$arrTest[] = array(true, 'ExB Language Crawler 2.1.5 (+http://www.exb.de/crawler)','ExB Language Crawler');
		$arrTest[] = array(true, 'coccoc/1.0 (http://help.coccoc.com/)','coccoc');
		$arrTest[] = array(true, 'Mozilla/5.0 (compatible; SiteExplorer/1.0b; +http://siteexplorer.info/)','SiteExplorer');
		$arrTest[] = array(true, 'iBusiness Shopcrawler','Shopcrawler');
		
		$arrTest[] = array(true, 'Mozilla/5.0 (compatible; BLEXBot/1.0; +http://webmeup.com/crawler.html)','BLEXBot');
		$arrTest[] = array(true, 'ExB Language Crawler 2.1.5 (+http://www.exb.de/crawler)','ExB Language Crawler');
		$arrTest[] = array(true, 'it2media-domain-crawler/1.0 on crawler-prod.it2media.de','www.adressendeutschland.de (it2)');
		$arrTest[] = array(true, 'Mozilla/5.0 (compatible; emefgebot/beta; +http://emefge.de/bot.html)','emefgebot');
		$arrTest[] = array(true, 'Mozilla/5.0 (compatible; CompSpyBot/1.0; +http://www.compspy.com/spider.html)','CompSpyBot');
		$arrTest[] = array(true, 'NCBot (http://netcomber.com : tool for finding true domain owners) Queries/complaints: bot@netcomber.com','NCBot');
		$arrTest[] = array(true, 'Mozilla/5.0 (compatible; Abonti/0.91 - http://www.abonti.com)','Abonti WebSearch');
		$arrTest[] = array(true, 'HubSpot Connect 1.0 (http://dev.hubspot.com/)','HubSpot');
		$arrTest[] = array(true, 'Mozilla/5.0 (compatible; meanpathbot/1.0; +http://www.meanpath.com/meanpathbot.html)','meanpathbot');
		$arrTest[] = array(true, 'Mozilla/5.0 (compatible; IstellaBot/1.10.2 +http://www.tiscali.it/)','IstellaBot');
		$arrTest[] = array(true, 'Mozilla/5.0 (compatible; Genieo/1.0 http://www.genieo.com/webfilter.html)','Genieo Web Filter');
		$arrTest[] = array(true, 'Cliqz Bot (+http://www.cliqz.com)','Cliqz Bot');
		$arrTest[] = array(true, 'BUbiNG (+http://law.di.unimi.it/BUbiNG.html)','BUbiNG Bot');
		$arrTest[] = array(true, 'Mozilla/5.0 (compatible; proximic; +http://www.proximic.com/info/spider.php)','proximic');
		$arrTest[] = array(true, 'Mozilla/5.0 (compatible; NetSeer crawler/2.0; +http://www.netseer.com/crawler.html; crawler@netseer.com)','NetSeer Crawler');
		$arrTest[] = array(true, 'AntBot/1.0 (http://www.ant.com)','AntBot');
		$arrTest[] = array(true, 'Cliqzbot/0.1 (+http://cliqz.com/company/cliqzbot)','Cliqzbot');
		$arrTest[] = array(true, 'Mozilla/5.0 (compatible; waybackarchive.org/1.0; +spider@waybackarchive.org)','Wayback Archive Bot');
		$arrTest[] = array(true, 'ImplisenseBot 1.0','ImplisenseBot');
		$arrTest[] = array(true, 'Riddler (http://riddler.io/about.html)','Riddler');
		//3.3.1
		$arrTest[] = array(true, 'Mozilla/4.0 (compatible; Blog Search;)','Blog Search');
		$arrTest[] = array(true, 'Mozilla/5.0 (compatible; publiclibraryarchive.org/1.0; +crawl@publiclibraryarchive.org)','publiclibraryarchive Bot');
		$arrTest[] = array(true, 'Pinterest/0.1 +http://pinterest.com/','Pinterest Bot');
		$arrTest[] = array(true, 'Mozilla/5.0 (compatible; ca-crawler/1.0)','ca-crawler');
		$arrTest[] = array(true, 'Mozilla/5.0 (compatible; 007ac9 Crawler; http://crawler.007ac9.net/)','007AC9 Crawler');
		$arrTest[] = array(true, 'dubaiindex (addressendeutschland.de)','addressendeutschland.de');
		$arrTest[] = array(true, 'thumbshots-de-bot (+http://www.thumbshots.de/)','thumbshots-de-bot');
		$arrTest[] = array(true, 'Mozilla/5.0 (compatible; memorybot/1.20.71 +http://archivethe.net/en/index.php/about/internet_memory1 on behalf of DNB)','memoryBot');
		$arrTest[] = array(true, 'stq_bot (+http://www.searchteq.de)','Searchteq Bot');
        //3.3.4
		$arrTest[] = array(true, 'python-requests/1.2.0 CPython/2.7.3 Linux/3.2.0-41-virtual','python-requests');
		$arrTest[] = array(true, 'Mechanize/2.0.1 Ruby/1.9.2p290 (http://github.com/tenderlove/mechanize/)','Mechanize');
		$arrTest[] = array(true, 'Ruby','Generic Ruby Crawler');
		$arrTest[] = array(true, 'MetaURI API/2.0 +metauri.com','MetaURI Bot');
		$arrTest[] = array(true, 'Crowsnest/0.5 (+http://www.crowsnest.tv/)','Crowsnest');
		$arrTest[] = array(true, 'NING/1.0','NING');
		$arrTest[] = array(true, 'publiclibraryarchive.org 1.0; +crawl@publiclibraryarchive.org','publiclibraryarchive.org');
		$arrTest[] = array(true, 'Cronjob.de','Cronjob.de');
		$arrTest[] = array(true, 'Mozilla/5.0 (compatible; MegaIndex.ru/2.0; +https://www.megaindex.ru/?tab=linkAnalyze)','MegaIndex Bot');
		//Fixed #12
		$arrTest[] = array(true, 'Mozilla/5.0 (compatible; DnyzBot/1.0)','DnyzBot');		
		
		$arrReferrerTest[] =array(false, 'contao.org');
		$arrReferrerTest[] =array(true, 'abcd4.de');
		$arrReferrerTest[] =array(true, 'backgroundpictures.net');
		$arrReferrerTest[] =array(true, 'baixar-musicas-gratis.com');
		$arrReferrerTest[] =array(true, 'buttons-for-website.com');
		$arrReferrerTest[] =array(true, 'cylex.de');
		$arrReferrerTest[] =array(true, 'bala.getenjoyment.net');
		$arrReferrerTest[] =array(true, 'darodar.com');
		$arrReferrerTest[] =array(true, 'extener.org');
		$arrReferrerTest[] =array(true, 'extener.com');
		$arrReferrerTest[] =array(true, 'embedle.com');
		$arrReferrerTest[] =array(true, 'fbfreegifts.com');
		$arrReferrerTest[] =array(true, 'feedouble.com');
		$arrReferrerTest[] =array(true, 'feedouble.net');
		$arrReferrerTest[] =array(true, 'joinandplay.me');
		$arrReferrerTest[] =array(true, 'joingames.org');
		$arrReferrerTest[] =array(true, 'japfm.com');
		$arrReferrerTest[] =array(true, 'kambasoft.com');
		$arrReferrerTest[] =array(true, 'lumb.co');
		$arrReferrerTest[] =array(true, 'myprintscreen.com');
		$arrReferrerTest[] =array(true, 'makemoneyonline.com');
		$arrReferrerTest[] =array(true, 'musicprojectfoundation.com');
		$arrReferrerTest[] =array(true, 'newworldmayfair.com');
		$arrReferrerTest[] =array(true, 'openfrost.com');
		$arrReferrerTest[] =array(true, 'openfrost.net');
		$arrReferrerTest[] =array(true, 'openmediasoft.com');
		$arrReferrerTest[] =array(true, 'srecorder.com');
		$arrReferrerTest[] =array(true, 'softomix.com');
		$arrReferrerTest[] =array(true, 'softomix.net');
		$arrReferrerTest[] =array(true, 'softomix.ru');
		$arrReferrerTest[] =array(true, 'sharebutton.net');
		$arrReferrerTest[] =array(true, 'soundfrost.org');
		$arrReferrerTest[] =array(true, 'srecorder.com');
		$arrReferrerTest[] =array(true, 'savetubevideo.com');
		$arrReferrerTest[] =array(true, 'semalt.com');
		$arrReferrerTest[] =array(true, 'tasteidea.com');
		$arrReferrerTest[] =array(true, 'vapmedia.org');
		$arrReferrerTest[] =array(true, 'videofrost.com');
		$arrReferrerTest[] =array(true, 'videofrost.net');
		$arrReferrerTest[] =array(true, 'youtubedownload.org');
		$arrReferrerTest[] =array(true, 'zazagames.org');
		
		$arrReferrerTest[] =array(true, 'updown_tester');
		$arrReferrerTest[] =array(true, 'azbukameha.ru');
		$arrReferrerTest[] =array(true, 'darkhalfe.ru');
		$arrReferrerTest[] =array(true, 'sovetogorod.ru');
		$arrReferrerTest[] =array(true, 'weesexy.ru');
		/*
wget --no-cache --referer="http://www.facebug.net/" --user-agent="Mozilla BugBuster" http://mars:81/vhosts/contao32_develop/
wget --no-cache --referer="https://16.semalt.com/crawler.php?u=http://gl.de" --user-agent="Mozilla BugBuster" http://mars:81/vhosts/contao32_develop/
		 */
		
		// localconfig Test
		$GLOBALS['BOTDETECTION']['BOT_AGENT'][] = array("Mozilla/5.0 (X11; U; Linux; en-US) AppleWebKit/531.2 (KHTML, like Gecko) Safari/531.2 localconfig","localconfig Bot");
		$arrTest[] = array(true, 'Mozilla/5.0 (X11; U; Linux; en-US) AppleWebKit/531.2 (KHTML, like Gecko) Safari/531.2 localconfig','localconfig Bot');
		
		//Output
	    echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="de">
<body>';
		echo '<div>';
    		echo '<div style="float:left;width:42%;font-family:Verdana,sans-serif;font-size: 12px;">';
    			$this->checkBotAgentTest($arrTest);
    		echo '</div>';
    		echo '<div style="float:left;width:58%;font-family:Verdana,sans-serif;font-size: 12px;">';
    			$this->checkBotAgentAdvancedTest($arrTest);
    		echo '</div>';
		echo '</div>';
		echo '<div style="clear:both;font-family:Verdana,sans-serif;font-size: 12px;"><br>';
			$this->checkBotIPTest();
		echo '</div>';
		$returnall = $this->checkBotAllTestsTest(); // muss vor ref test, sonst geht erster Test schief
		echo '<div style="clear:both;font-family:Verdana,sans-serif;font-size: 12px;"><br>';
            $this->checkBotReferrerTest($arrReferrerTest);
		echo '</div>';
		echo '<div style="clear:both;font-family:Verdana,sans-serif;font-size: 12px;"><br>';
		    echo $returnall;
		echo '</div>';
		echo "<h2>ModuleBotDetection Version: ".$this->getVersion()."</h2>";
		echo "</body></html>";
	} 
	
	private function checkBotAgentTest($arrTest)
	{
	    echo "<h1>CheckBotAgentTest</h1>";

	    $y=count($arrTest);
	    for ($x=0; $x<$y; $x++)
	    {
	        $result[$x] = \BugBuster\BotDetection\CheckBotAgentSimple::checkAgent($arrTest[$x][1]);
	    }
	    for ($x=0; $x<$y; $x++)
	    {
	        $nr = ($x<10) ? "&nbsp;".$x : $x;
	        if ($arrTest[$x][0] == $result[$x]) 
	        {
	        	echo '<span style="color:green;">';
	        } 
	        else 
	        {
	            echo '<span style="color:red;">';
	        }
	        echo "TestNr: ". $nr ."&nbsp;&nbsp;Expectation/Result: ".var_export($arrTest[$x][0],true)."/".var_export($result[$x],true)." (".$arrTest[$x][2].")";
	        echo "</span><br>";
	    }
		
		return true;
	}
	
	private function checkBotIPTest()
	{
	    echo "<h1>CheckBotIPTest</h1>";
	    $arrTest[] = array(false,false,'own IP');
	    $arrTest[] = array(false,'74.125.79.100','74.125.79.100 - Google Plus');
	    $arrTest[] = array(true ,'192.114.71.13','192.114.71.13 - web spider israel');
	    $arrTest[] = array(true ,'65.55.231.74' ,'65.55.231.74 in 65.52.0.0/14 - MSN Net');
	    $arrTest[] = array(true ,'66.249.95.222','66.249.95.222 in 66.249.64.0/19 - Google Net');
	    $arrTest[] = array(true ,'2001:4860:4801:1109:0:6006:1300:b075','2001:4860:4801:1109:0:6006:1300:b075 - Google Bot IPv6');
	    $arrTest[] = array(false,'2001:0db8:85a3:08d3:1319:8a2e:0370:7334','2001:0db8:85a3:08d3:1319:8a2e:0370:7334 - No Bot');
	    $arrTest[] = array(false,'2001:0db8:85a3:08d3:1319:8a2e:0370:7334','2001:0db8:85a3:08d3:1319:8a2e:0370:7334 - No Bot');
	    $arrTest[] = array(false,'::ffff:c000:280'   ,'::ffff:c000:280    - double quad notation for ipv4 mapped addresses');
	    $arrTest[] = array(false,'::ffff:192.0.2.128','::ffff:192.0.2.128 - double quad notation for ipv4 mapped addresses');
	    
	    \BugBuster\BotDetection\CheckBotIp::setBotIpv4List(TL_ROOT . self::BOT_IP4_LIST);
	    \BugBuster\BotDetection\CheckBotIp::setBotIpv6List(TL_ROOT . self::BOT_IP6_LIST);
	    
	    $y=count($arrTest);
	    for ($x=0; $x<$y; $x++)
	    {
	        $result[$x] = \BugBuster\BotDetection\CheckBotIp::checkIP($arrTest[$x][1]);
	    }
	    for ($x=0; $x<$y; $x++)
	    {
	        $nr = ($x<10) ? "&nbsp;".$x : $x;
	        if ($arrTest[$x][0] == $result[$x]) {
	        	echo '<span style="color:green;">';
	        } else 
	        {
	            echo '<span style="color:red;">';
	        }
	        echo "TestNr: ". $nr ."&nbsp;&nbsp;Expectation/Result: ".var_export($arrTest[$x][0],true)."/".var_export($result[$x],true)." (".$arrTest[$x][2].")";
	        echo "</span><br>";
	    }

	    return true;
	}
	
	private function checkBotAgentAdvancedTest($arrTest)
	{
	    echo "<h1>CheckBotAdvancedTest</h1>";
	    $y=count($arrTest);
	    for ($x=0; $x<$y; $x++)
	    {
	        $result[$x] = \BugBuster\BotDetection\CheckBotAgentExtended::checkAgentName($arrTest[$x][1]);
	    }
	    for ($x=0; $x<$y; $x++)
	    {
	        $nr = ($x<10) ? "&nbsp;".$x : $x;
	        if ($arrTest[$x][0] === false) // false Test 
	        {
	            if ($arrTest[$x][0] == $result[$x]) 
    	        {
    	        	echo '<span style="color:green;">';
    	        } 
    	        else 
    	        {
    	            echo '<span style="color:red;">';
    	        }
	            
	        }
	        else // true Test
	        {
    	        if ($arrTest[$x][2] == $result[$x]) //$arrTest[$x][0] 
    	        {
    	        	echo '<span style="color:green;">';
    	        } 
    	        else 
    	        {
    	            echo '<span style="color:red;">';
    	        }
	        }
	        echo "TestNr: ". $nr ."&nbsp;&nbsp;Expectation/Result: ".var_export($arrTest[$x][0],true)."/".var_export($result[$x],true)." (".$arrTest[$x][2].")";
	        echo "</span><br>";
	    }
	
		return true;
	}
	
	private function checkBotAllTestsTest()
	{
	    $return = "<h1>CheckBotAllTest</h1>";
	    $arrTest[0] = 'your browser, false test';
	    $arrTest[1] = 'CheckBotAgent test';
	    $arrTest[2] = 'CheckBotAgentAdvanced test';
	    $arrTest[3] = 'CheckBotIP test';
	    $result[0] = !$this->checkBotAllTests();
	    $result[1] = $this->checkBotAllTests('Spider test'); //BD_CheckBotAgent = true 
	    $result[2] = $this->checkBotAllTests('acadiauniversitywebcensusclient'); //BD_CheckBotAgentAdvanced = true
	    //set own IP as Bot IP
	    if (\Environment::get('remoteAddr'))
	    {
	        if (strpos(\Environment::get('remoteAddr'), ',') !== false) //first IP
	        {
	            $ip = trim(substr(\Environment::get('remoteAddr'), 0, strpos(\Environment::get('remoteAddr'), ',')));
	            // Test for IPv4
	            if (ip2long($ip) !== false)
	            {
	                $GLOBALS['BOTDETECTION']['BOT_IP'][] = $ip;
	            }
	            else 
	            {
	                $GLOBALS['BOTDETECTION']['BOT_IPV6'][] = $ip;
	            }
                
	        }
	        else
	        {
	            $ip = trim(\Environment::get('remoteAddr'));
	            // Test for IPv4
	            if (ip2long($ip) !== false)
	            {
	                $GLOBALS['BOTDETECTION']['BOT_IP'][] = $ip;
	            }
	            else 
	            {
	                $GLOBALS['BOTDETECTION']['BOT_IPV6'][] = $ip;
	            }
	        }
	        $result[3] = $this->checkBotAllTests(); //BD_CheckBotIP = true
	    }

	    //output
	    for ($x=0; $x<4; $x++)
	    {
    	    $nr = ($x<10) ? "&nbsp;".$x : $x;
            if (true === $result[$x]) 
            {
	            $return .= '<span style="color:green;">';
	        } 
	        else
	        {
	            $return .= '<span style="color:red;">';
	        }
            $return .= "TestNr: ". $nr ."&nbsp;&nbsp;Expectation/Result: true/".var_export($result[$x],true)." (".$arrTest[$x].")";
            $return .= "</span><br>";
	    }
        return $return;	    	    
	}
	
	private function checkBotReferrerTest($arrReferrerTest)
	{
	    
	    echo "<h1>CheckBotReferrerTest</h1>";
	    $y=count($arrReferrerTest);
	    for ($x=0; $x<$y; $x++)
	    {
            $result[$x] = \BugBuster\BotDetection\CheckBotReferrer::checkReferrer('http://'.$arrReferrerTest[$x][1].'/wtf',
                                    $this->rootDir ."/vendor/bugbuster/contao-botdetection-bundle/src/Resources/contao/config/bot-referrer-list.php",
                                    $this->rootDir ."/vendor/bugbuster/contao-botdetection-bundle/src/Resources/contao/config/referrer-provider.php");
	    }
	    for ($x=0; $x<$y; $x++)
	    {
	        $nr = ($x<10) ? "&nbsp;".$x : $x;
	        if ($arrReferrerTest[$x][0] === false) // false Test
            {
	            if ($arrReferrerTest[$x][0] == $result[$x])
	            {
	               echo '<span style="color:green;">';
                }
                else
                {
                    echo '<span style="color:red;">';
                }
            }
            else // true Test
            {
                if ($arrReferrerTest[$x][1] == $result[$x]) 
                {
                    echo '<span style="color:green;">';
                }
                else
    	        {
                     echo '<span style="color:red;">';
    	        }
	        }
	        echo "TestNr: ". $nr ."&nbsp;&nbsp;Expectation/Result: ".var_export($arrReferrerTest[$x][0],true)."/".var_export($result[$x],true)." (".$arrReferrerTest[$x][1].")";
	        echo "</span><br>";
	    }

		return true;
	}
	
} // class

/**
 * Instantiate controller
 */
$objBotDetectionTest = new ModuleBotDetectionTest();
$objBotDetectionTest->run();

