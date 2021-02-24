<?php

declare(strict_types=1);

/*
 * This file is part of a BugBuster Contao Bundle
 *
 * @copyright  Glen Langer 2020 <http://contao.ninja>
 * @author     Glen Langer (BugBuster)
 * @package    Contao BotDetection Bundle
 * @license    LGPL-3.0-or-later
 * @see        https://github.com/BugBuster1701/contao-botdetection-bundle
 */

namespace BugBuster\BotdetectionBundle\Functional;

use BugBuster\BotDetection\ModuleBotDetection;

/**
 * Class Tests.
 *
 * @copyright  Glen Langer 2007..2020 <http://contao.ninja>
 */
class BotDetectionTests extends ModuleBotDetection
{
    /**
     * Initialize object.
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function run(): string
    {
        set_time_limit(300);
        $out = '';
        // AGENT TEST DEFINITIONS

        $arrTest[] = [false, false, 'your browser']; // own Browser
        //Browser
        $arrTest[] = [false, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3', 'Firefox'];
        $arrTest[] = [false, 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.2; Trident/4.0; Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1);', 'IE8.0'];
        $arrTest[] = [false, 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; de; rv:1.9.2.2) Gecko/20100316 Firefox/3.6.2', 'Macintosh FF'];
        $arrTest[] = [false, 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_3; de-de) AppleWebKit/531.22.7 (KHTML, like Gecko) Version/4.0.5 Safari/531.22.7', 'Macintosh Safari'];
        //Bots
        $arrTest[] = [true, 'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)', 'Google Bot'];
        $arrTest[] = [true, 'ia_archiver (+http://www.alexa.com/site/help/webmasters; crawler@alexa.com)', 'Internet Archive'];
        $arrTest[] = [true, 'Yandex/1.01.001 (compatible; Win16; P)', 'Yandex'];
        $arrTest[] = [true, 'Mozilla/5.0 (compatible; Yahoo! Slurp; http://help.yahoo.com/help/us/ysearch/slurp)', 'Yahoo! Slurp'];
        $arrTest[] = [true, 'Mozilla/5.0 (compatible; Exabot/3.0; +http://www.exabot.com/go/robot)', 'Exabot'];
        $arrTest[] = [true, 'msnbot/2.0b (+http://search.msn.com/msnbot.htm)', 'msnbot'];
        $arrTest[] = [true, 'Mozilla/5.0 (Twiceler-0.9 http://www.cuil.com/twiceler/robot.html)', 'Twiceler'];
        $arrTest[] = [true, 'Googlebot-Image/1.0', 'Google Image Search'];
        $arrTest[] = [true, 'Yeti/1.0 (NHN Corp.; http://help.naver.com/robots/)', 'NaverBot'];
        $arrTest[] = [true, 'Baiduspider+(+http://www.baidu.com/search/spider.htm)', 'Baiduspider'];
        $arrTest[] = [true, 'Mozilla/5.0 (compatible; spbot/2.0.2; +http://www.seoprofiler.com/bot/ )', 'SEOprofiler'];
        $arrTest[] = [true, 'ia_archiver-web.archive.org', 'Internet Archive'];
        $arrTest[] = [true, 'Mozilla/5.0 (compatible; archive.org_bot +http://www.archive.org/details/archive.org_bot)', 'Internet Archive'];

        $arrTest[] = [true, 'msnbot-media/1.1 (+http://search.msn.com/msnbot.htm)', 'msnbot-media'];
        $arrTest[] = [true, 'Mozilla/5.0 (compatible; Ask Jeeves/Teoma; +http://about.ask.com/en/docs/about/webmasters.shtml)', 'Teoma'];
        $arrTest[] = [true, 'TrueKnowledgeBot (http://www.trueknowledge.com/tkbot/; tkbot -AT- trueknowledge _dot_ com)', 'TrueKnowledgeBot'];
        $arrTest[] = [true, 'Mozilla/5.0 (compatible; ptd-crawler; +http://bixolabs.com/crawler/ptd/; crawler@bixolabs.com)', 'ptd-crawler bixolabs.com'];
        $arrTest[] = [true, 'Cityreview Robot (+http://www.cityreview.org/crawler/)', 'Cityreview Robot'];
        $arrTest[] = [true, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9) Gecko/2008052906 Firefox/3.0/1.0 (bot; http://)', 'No-Name-Bot'];
        $arrTest[] = [true, 'Mozilla/5.0 (en-us) AppleWebKit/525.13 (KHTML, like Gecko; Google Web Preview) Version/3.1 Safari/525.13', 'Google Web Preview'];

        $arrTest[] = [true, 'Mozilla/5.0 (compatible; Spiderlytics/1.0; +spider@spiderlytics.com)', 'Spiderlytics'];
        $arrTest[] = [true, 'ExB Language Crawler 2.1.5 (+http://www.exb.de/crawler)', 'ExB Language Crawler'];
        $arrTest[] = [true, 'coccoc/1.0 (http://help.coccoc.com/)', 'coccoc'];
        $arrTest[] = [true, 'Mozilla/5.0 (compatible; SiteExplorer/1.0b; +http://siteexplorer.info/)', 'SiteExplorer'];
        $arrTest[] = [true, 'iBusiness Shopcrawler', 'Shopcrawler'];

        $arrTest[] = [true, 'Mozilla/5.0 (compatible; BLEXBot/1.0; +http://webmeup.com/crawler.html)', 'BLEXBot'];
        $arrTest[] = [true, 'ExB Language Crawler 2.1.5 (+http://www.exb.de/crawler)', 'ExB Language Crawler'];
        $arrTest[] = [true, 'it2media-domain-crawler/1.0 on crawler-prod.it2media.de', 'www.adressendeutschland.de (it2)'];
        $arrTest[] = [true, 'Mozilla/5.0 (compatible; emefgebot/beta; +http://emefge.de/bot.html)', 'emefgebot'];
        $arrTest[] = [true, 'Mozilla/5.0 (compatible; CompSpyBot/1.0; +http://www.compspy.com/spider.html)', 'CompSpyBot'];
        $arrTest[] = [true, 'NCBot (http://netcomber.com : tool for finding true domain owners) Queries/complaints: bot@netcomber.com', 'NCBot'];
        $arrTest[] = [true, 'Mozilla/5.0 (compatible; Abonti/0.91 - http://www.abonti.com)', 'Abonti WebSearch'];
        $arrTest[] = [true, 'HubSpot Connect 1.0 (http://dev.hubspot.com/)', 'HubSpot Webcrawler'];
        $arrTest[] = [true, 'Mozilla/5.0 (compatible; meanpathbot/1.0; +http://www.meanpath.com/meanpathbot.html)', 'meanpathbot'];
        $arrTest[] = [true, 'Mozilla/5.0 (compatible; IstellaBot/1.10.2 +http://www.tiscali.it/)', 'IstellaBot'];
        $arrTest[] = [true, 'Mozilla/5.0 (compatible; Genieo/1.0 http://www.genieo.com/webfilter.html)', 'Genieo Web Filter'];
        $arrTest[] = [true, 'Cliqz Bot (+http://www.cliqz.com)', 'Cliqz Bot'];
        $arrTest[] = [true, 'BUbiNG (+http://law.di.unimi.it/BUbiNG.html)', 'BUbiNG Bot'];
        $arrTest[] = [true, 'Mozilla/5.0 (compatible; proximic; +http://www.proximic.com/info/spider.php)', 'proximic'];
        $arrTest[] = [true, 'Mozilla/5.0 (compatible; NetSeer crawler/2.0; +http://www.netseer.com/crawler.html; crawler@netseer.com)', 'NetSeer Crawler'];
        $arrTest[] = [true, 'AntBot/1.0 (http://www.ant.com)', 'AntBot'];
        $arrTest[] = [true, 'Cliqzbot/0.1 (+http://cliqz.com/company/cliqzbot)', 'Cliqzbot'];
        $arrTest[] = [true, 'Mozilla/5.0 (compatible; waybackarchive.org/1.0; +spider@waybackarchive.org)', 'Wayback Archive Bot'];
        $arrTest[] = [true, 'ImplisenseBot 1.0', 'ImplisenseBot'];
        $arrTest[] = [true, 'Riddler (http://riddler.io/about.html)', 'Riddler'];
        //3.3.1
        $arrTest[] = [true, 'Mozilla/4.0 (compatible; Blog Search;)', 'Blog Search'];
        $arrTest[] = [true, 'Mozilla/5.0 (compatible; publiclibraryarchive.org/1.0; +crawl@publiclibraryarchive.org)', 'publiclibraryarchive Bot'];
        $arrTest[] = [true, 'Pinterest/0.1 +http://pinterest.com/', 'Pinterest Bot'];
        $arrTest[] = [true, 'Mozilla/5.0 (compatible; ca-crawler/1.0)', 'ca-crawler'];
        $arrTest[] = [true, 'Mozilla/5.0 (compatible; 007ac9 Crawler; http://crawler.007ac9.net/)', '007AC9 Crawler'];
        $arrTest[] = [true, 'dubaiindex (addressendeutschland.de)', 'addressendeutschland.de'];
        $arrTest[] = [true, 'thumbshots-de-bot (+http://www.thumbshots.de/)', 'thumbshots-de-bot'];
        $arrTest[] = [true, 'Mozilla/5.0 (compatible; memorybot/1.20.71 +http://archivethe.net/en/index.php/about/internet_memory1 on behalf of DNB)', 'memoryBot'];
        $arrTest[] = [true, 'stq_bot (+http://www.searchteq.de)', 'Searchteq Bot'];
        //3.3.4
        $arrTest[] = [true, 'python-requests/1.2.0 CPython/2.7.3 Linux/3.2.0-41-virtual', 'python-requests'];
        $arrTest[] = [true, 'Mechanize/2.0.1 Ruby/1.9.2p290 (http://github.com/tenderlove/mechanize/)', 'Mechanize'];
        $arrTest[] = [true, 'Ruby', 'Generic Ruby Crawler'];
        $arrTest[] = [true, 'MetaURI API/2.0 +metauri.com', 'MetaURI Bot'];
        $arrTest[] = [true, 'Crowsnest/0.5 (+http://www.crowsnest.tv/)', 'Crowsnest'];
        $arrTest[] = [true, 'NING/1.0', 'NING'];
        $arrTest[] = [true, 'publiclibraryarchive.org 1.0; +crawl@publiclibraryarchive.org', 'publiclibraryarchive.org'];
        $arrTest[] = [true, 'Cronjob.de', 'Cronjob.de'];
        $arrTest[] = [true, 'Mozilla/5.0 (compatible; MegaIndex.ru/2.0; +https://www.megaindex.ru/?tab=linkAnalyze)', 'MegaIndex Bot'];
        //Fixed #12
        $arrTest[] = [true, 'Mozilla/5.0 (compatible; DnyzBot/1.0)', 'DnyzBot'];
        //Fixed #21
        $arrTest[] = [true, 'check_http/v2.2 (monitoring-plugins 2.2)', 'check_mk monitoring-plugin'];
        //Fixed #19
        $arrTest[] = [true, 'Mozilla/5.0 (compatible; phpservermon/3.2.2; +http://www.phpservermonitor.org)', 'phpservermonitor'];
        //Fixed #20
        $arrTest[] = [true, 'CFNetwork/', 'CFNetwork App'];
        //Fixed #34
        $arrTest[] = [true, 'Mozilla/5.0 (compatible; Barkrowler/0.9; +https://babbar.tech/crawler)', 'Barkrowler'];
        //Fixed #33
        $arrTest[] = [true, 'Mozilla/5.0 (Linux; Android 7.0;) AppleWebKit/537.36 (KHTML, like Gecko) Mobile Safari/537.36 (compatible; PetalBot;+http://aspiegel.com/petalbot)', 'PetalBot'];
        //Fixed #42
        $arrTest[] = [true, 'Nuclei - Open-source project (github.com/projectdiscovery/nuclei)', 'Nuclei Vulnerability Scanner'];

        $arrReferrerTest[] = [false, 'contao.org'];
        $arrReferrerTest[] = [true, 'abcd4.de'];
        $arrReferrerTest[] = [true, 'backgroundpictures.net'];
        $arrReferrerTest[] = [true, 'baixar-musicas-gratis.com'];
        $arrReferrerTest[] = [true, 'buttons-for-website.com'];
        $arrReferrerTest[] = [true, 'cylex.de'];
        $arrReferrerTest[] = [true, 'bala.getenjoyment.net'];
        $arrReferrerTest[] = [true, 'darodar.com'];
        $arrReferrerTest[] = [true, 'extener.org'];
        $arrReferrerTest[] = [true, 'extener.com'];
        $arrReferrerTest[] = [true, 'embedle.com'];
        $arrReferrerTest[] = [true, 'fbfreegifts.com'];
        $arrReferrerTest[] = [true, 'feedouble.com'];
        $arrReferrerTest[] = [true, 'feedouble.net'];
        $arrReferrerTest[] = [true, 'joinandplay.me'];
        $arrReferrerTest[] = [true, 'joingames.org'];
        $arrReferrerTest[] = [true, 'japfm.com'];
        $arrReferrerTest[] = [true, 'kambasoft.com'];
        $arrReferrerTest[] = [true, 'lumb.co'];
        $arrReferrerTest[] = [true, 'myprintscreen.com'];
        $arrReferrerTest[] = [true, 'makemoneyonline.com'];
        $arrReferrerTest[] = [true, 'musicprojectfoundation.com'];
        $arrReferrerTest[] = [true, 'newworldmayfair.com'];
        $arrReferrerTest[] = [true, 'openfrost.com'];
        $arrReferrerTest[] = [true, 'openfrost.net'];
        $arrReferrerTest[] = [true, 'openmediasoft.com'];
        $arrReferrerTest[] = [true, 'srecorder.com'];
        $arrReferrerTest[] = [true, 'softomix.com'];
        $arrReferrerTest[] = [true, 'softomix.net'];
        $arrReferrerTest[] = [true, 'softomix.ru'];
        $arrReferrerTest[] = [true, 'sharebutton.net'];
        $arrReferrerTest[] = [true, 'soundfrost.org'];
        $arrReferrerTest[] = [true, 'srecorder.com'];
        $arrReferrerTest[] = [true, 'savetubevideo.com'];
        $arrReferrerTest[] = [true, 'semalt.com'];
        $arrReferrerTest[] = [true, 'tasteidea.com'];
        $arrReferrerTest[] = [true, 'vapmedia.org'];
        $arrReferrerTest[] = [true, 'videofrost.com'];
        $arrReferrerTest[] = [true, 'videofrost.net'];
        $arrReferrerTest[] = [true, 'youtubedownload.org'];
        $arrReferrerTest[] = [true, 'zazagames.org'];

        $arrReferrerTest[] = [true, 'updown_tester'];
        $arrReferrerTest[] = [true, 'azbukameha.ru'];
        $arrReferrerTest[] = [true, 'darkhalfe.ru'];
        $arrReferrerTest[] = [true, 'sovetogorod.ru'];
        $arrReferrerTest[] = [true, 'weesexy.ru'];
        /*
wget --no-cache --referer="http://www.facebug.net/" --user-agent="Mozilla BugBuster" http://mars:81/vhosts/contao32_develop/
wget --no-cache --referer="https://16.semalt.com/crawler.php?u=http://gl.de" --user-agent="Mozilla BugBuster" http://mars:81/vhosts/contao32_develop/
         */

        // localconfig Test
        $GLOBALS['BOTDETECTION']['BOT_AGENT'][] = ['Mozilla/5.0 (X11; U; Linux; en-US) AppleWebKit/531.2 (KHTML, like Gecko) Safari/531.2 localconfig', 'localconfig Bot'];
        $arrTest[] = [true, 'Mozilla/5.0 (X11; U; Linux; en-US) AppleWebKit/531.2 (KHTML, like Gecko) Safari/531.2 localconfig', 'localconfig Bot'];

        //Output

        $out .= '<!DOCTYPE html>
<html lang="de">
<body>
    <div>
	        <div style="float:left;width:42%;font-family:Verdana,sans-serif;font-size: 12px;">';
        $out .= $this->checkBotAgentTest($arrTest);
        $out .= '	        </div>
            <div style="float:left;width:58%;font-family:Verdana,sans-serif;font-size: 12px;">';
        $out .= $this->checkBotAgentAdvancedTest($arrTest);
        $out .= '	        </div>';
        $out .= '    </div>';
        $out .= '<div style="clear:both;font-family:Verdana,sans-serif;font-size: 12px;"><br>';
        $out .= $this->checkBotIPTest();
        $out .= '</div>';
        $returnall = $this->checkBotAllTestsTest(); // muss vor ref test, sonst geht erster Test schief
        $out .= '<div style="clear:both;font-family:Verdana,sans-serif;font-size: 12px;"><br>';
        $out .= $this->checkBotReferrerTest($arrReferrerTest);
        $out .= '</div>';
        $out .= '<div style="clear:both;font-family:Verdana,sans-serif;font-size: 12px;"><br>';
        $out .= $returnall;
        $out .= '</div>';
        echo $out;
        ob_flush();
        flush();
        $this->testCrawlerDetectCrawlers();
        $this->testCrawlerDetectDevices();
        $out = '<h2>ModuleBotDetection Version: '.$this->getVersion().'</h2>';
        $out .= '</body></html>';
        echo $out;

        return '';
    }

    private function checkBotAgentTest(array $arrTest): string
    {
        $out = '<h1>CheckBotAgentTest</h1>';

        $y = \count($arrTest);
        for ($x = 0; $x < $y; ++$x) {
            $result[$x] = \BugBuster\BotDetection\CheckBotAgentSimple::checkAgent($arrTest[$x][1]);
        }
        for ($x = 0; $x < $y; ++$x) {
            $nr = ($x < 10) ? '&nbsp;'.$x : $x;
            if ($arrTest[$x][0] === $result[$x]) {
                $out .= '<span style="color:green;">';
            } else {
                $out .= '<span style="color:red;">';
            }
            $out .= 'TestNr: '.$nr.'&nbsp;&nbsp;Expectation/Result: '.var_export($arrTest[$x][0], true).'/'.var_export($result[$x], true).' ('.$arrTest[$x][2].')';
            $out .= '</span><br>';
        }

        return $out;
    }

    private function checkBotIPTest(): string
    {
        $out = '<h1>CheckBotIPTest</h1>';
        $arrTest[] = [false, false, 'own IP'];
        $arrTest[] = [false, '74.125.79.100', '74.125.79.100 - Google Plus'];
        $arrTest[] = [true, '192.114.71.13', '192.114.71.13 - web spider israel'];
        $arrTest[] = [true, '65.55.231.74', '65.55.231.74 in 65.52.0.0/14 - MSN Net'];
        $arrTest[] = [true, '66.249.95.222', '66.249.95.222 in 66.249.64.0/19 - Google Net'];
        $arrTest[] = [true, '2001:4860:4801:1109:0:6006:1300:b075', '2001:4860:4801:1109:0:6006:1300:b075 - Google Bot IPv6'];
        $arrTest[] = [false, '2001:0db8:85a3:08d3:1319:8a2e:0370:7334', '2001:0db8:85a3:08d3:1319:8a2e:0370:7334 - No Bot'];
        $arrTest[] = [false, '2001:0db8:85a3:08d3:1319:8a2e:0370:7334', '2001:0db8:85a3:08d3:1319:8a2e:0370:7334 - No Bot'];
        $arrTest[] = [false, '::ffff:c000:280', '::ffff:c000:280    - double quad notation for ipv4 mapped addresses'];
        $arrTest[] = [false, '::ffff:192.0.2.128', '::ffff:192.0.2.128 - double quad notation for ipv4 mapped addresses'];

        \BugBuster\BotDetection\CheckBotIp::setBotIpv4List($this->rootDir.self::BOT_IP4_LIST);
        \BugBuster\BotDetection\CheckBotIp::setBotIpv6List($this->rootDir.self::BOT_IP6_LIST);

        $y = \count($arrTest);
        for ($x = 0; $x < $y; ++$x) {
            $result[$x] = \BugBuster\BotDetection\CheckBotIp::checkIP($arrTest[$x][1]);
        }
        for ($x = 0; $x < $y; ++$x) {
            $nr = ($x < 10) ? '&nbsp;'.$x : $x;
            if ($arrTest[$x][0] === $result[$x]) {
                $out .= '<span style="color:green;">';
            } else {
                $out .= '<span style="color:red;">';
            }
            $out .= 'TestNr: '.$nr.'&nbsp;&nbsp;Expectation/Result: '.var_export($arrTest[$x][0], true).'/'.var_export($result[$x], true).' ('.$arrTest[$x][2].')';
            $out .= '</span><br>';
        }

        return $out;
    }

    private function checkBotAgentAdvancedTest(array $arrTest): string
    {
        $out = '<h1>CheckBotAdvancedTest</h1>';
        $y = \count($arrTest);
        for ($x = 0; $x < $y; ++$x) {
            $result[$x] = \BugBuster\BotDetection\CheckBotAgentExtended::checkAgentName($arrTest[$x][1]);
        }
        for ($x = 0; $x < $y; ++$x) {
            $nr = ($x < 10) ? '&nbsp;'.$x : $x;
            if (false === $arrTest[$x][0]) { // false Test
                if ($arrTest[$x][0] === $result[$x]) {
                    $out .= '<span style="color:green;">';
                } else {
                    $out .= '<span style="color:red;">';
                }
            } else { // true Test
                if ($arrTest[$x][2] === $result[$x]) { //$arrTest[$x][0]
                    $out .= '<span style="color:green;">';
                } else {
                    $out .= '<span style="color:red;">';
                }
            }
            $out .= 'TestNr: '.$nr.'&nbsp;&nbsp;Expectation/Result: '.var_export($arrTest[$x][0], true).'/'.var_export($result[$x], true).' ('.$arrTest[$x][2].')';
            $out .= '</span><br>';
        }

        return $out;
    }

    private function checkBotAllTestsTest(): string
    {
        $return = '<h1>CheckBotAllTest</h1>';
        $arrTest[0] = 'your browser, false test';
        $arrTest[1] = 'CheckBotAgent test';
        $arrTest[2] = 'CheckBotAgentAdvanced test';
        $arrTest[3] = 'CheckBotIP test';
        $result[0] = !$this->checkBotAllTests();
        $result[1] = $this->checkBotAllTests('Spider test'); //BD_CheckBotAgent = true
        $result[2] = $this->checkBotAllTests('acadiauniversitywebcensusclient'); //BD_CheckBotAgentAdvanced = true
        //set own IP as Bot IP
        if (\Contao\Environment::get('remoteAddr')) {
            if (false !== strpos(\Contao\Environment::get('remoteAddr'), ',')) { //first IP
                $ip = trim(substr(\Contao\Environment::get('remoteAddr'), 0, strpos(\Contao\Environment::get('remoteAddr'), ',')));
                // Test for IPv4
                if (false !== ip2long($ip)) {
                    $GLOBALS['BOTDETECTION']['BOT_IP'][] = $ip;
                } else {
                    $GLOBALS['BOTDETECTION']['BOT_IPV6'][] = $ip;
                }
            } else {
                $ip = trim(\Contao\Environment::get('remoteAddr'));
                // Test for IPv4
                if (false !== ip2long($ip)) {
                    $GLOBALS['BOTDETECTION']['BOT_IP'][] = $ip;
                } else {
                    $GLOBALS['BOTDETECTION']['BOT_IPV6'][] = $ip;
                }
            }
            $arrTest[3] = 'CheckBotIP test ('.$ip.')';
            $result[3] = $this->checkBotAllTests(); //BD_CheckBotIP = true
        }

        //output
        for ($x = 0; $x < 4; ++$x) {
            $nr = ($x < 10) ? '&nbsp;'.$x : $x;
            if (true === $result[$x]) {
                $return .= '<span style="color:green;">';
            } else {
                $return .= '<span style="color:red;">';
            }
            $return .= 'TestNr: '.$nr.'&nbsp;&nbsp;Expectation/Result: true/'.var_export($result[$x], true).' ('.$arrTest[$x].')';
            $return .= '</span><br>';
        }

        return $return;
    }

    private function checkBotReferrerTest(array $arrReferrerTest): string
    {
        $out = '<h1>CheckBotReferrerTest</h1>';
        $y = \count($arrReferrerTest);
        for ($x = 0; $x < $y; ++$x) {
            $result[$x] = \BugBuster\BotDetection\CheckBotReferrer::checkReferrer('http://'.$arrReferrerTest[$x][1].'/wtf',
                                    $this->rootDir.'/vendor/bugbuster/contao-botdetection-bundle/src/Resources/contao/config/bot-referrer-list.php',
                                    $this->rootDir.'/vendor/bugbuster/contao-botdetection-bundle/src/Resources/contao/config/referrer-provider.php');
        }
        for ($x = 0; $x < $y; ++$x) {
            $nr = ($x < 10) ? '&nbsp;'.$x : $x;
            if (false === $arrReferrerTest[$x][0]) { // false Test
                if ($arrReferrerTest[$x][0] === $result[$x]) {
                    $out .= '<span style="color:green;">';
                } else {
                    $out .= '<span style="color:red;">';
                }
            } else { // true Test
                if ($arrReferrerTest[$x][0] === $result[$x]) {
                    $out .= '<span style="color:green;">';
                } else {
                    $out .= '<span style="color:red;">';
                }
            }
            $out .= 'TestNr: '.$nr.'&nbsp;&nbsp;Expectation/Result: '.var_export($arrReferrerTest[$x][0], true).'/'.var_export($result[$x], true).' ('.$arrReferrerTest[$x][1].')';
            $out .= '</span><br>';
        }

        return $out;
    }

    private function testCrawlerDetectCrawlers(): void
    {
        $out = '<h1>testCrawlerDetectCrawlers</h1>';
        echo $out;

        $lines = file($this->rootDir.'/vendor/bugbuster/contao-botdetection-bundle/src/Functional/crawlers.txt', \FILE_IGNORE_NEW_LINES | \FILE_SKIP_EMPTY_LINES);
        $rows = 0;
        foreach ($lines as $line) {
            $out = '';
            $result1 = \BugBuster\BotDetection\CheckBotAgentExtended::checkAgentViaCrawlerDetect($line, true);
            if ($result1) {
                $out .= '<span style="color:green;">.</span>';
                ++$rows;
                if ($rows > 320) {
                    $out .= "<br>\n";
                    $rows = 0;
                    ob_flush();
                    flush();
                }
            } else {
                $out .= '<br><span style="color:red;">';
                $out .= (int) $result1.'|'.$result1.'|'.$line;
                $out .= '</span><br>';
            }
            echo $out;
        }
    }

    private function testCrawlerDetectDevices(): void
    {
        $out = '<h1>testCrawlerDetectDevices</h1>';
        echo $out;
        $lines = file($this->rootDir.'/vendor/bugbuster/contao-botdetection-bundle/src/Functional/devices.txt', \FILE_IGNORE_NEW_LINES | \FILE_SKIP_EMPTY_LINES);
        $rows = 0;
        foreach ($lines as $line) {
            $out = '';
            $result1 = \BugBuster\BotDetection\CheckBotAgentExtended::checkAgentViaCrawlerDetect($line, true);
            if ($result1) {
                $out .= '<br><span style="color:red;">';
                $out .= (int) $result1.'|'.$result1.'|'.$line;
                $out .= '</span><br>';
            } else {
                $out .= '<span style="color:green;">.</span>';
                ++$rows;
                if ($rows > 320) {
                    $out .= "<br>\n";
                    $rows = 0;
                    ob_flush();
                    flush();
                }
            }
            echo $out;
        }
    }
} // class
