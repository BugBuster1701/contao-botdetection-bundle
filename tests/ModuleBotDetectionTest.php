<?php


use BugBuster\BotDetection\ModuleBotDetection;
use BugBuster\BotDetection\CheckBotIp;
use BugBuster\BotDetection\CheckCloudIp;
//use Contao\CoreBundle\Tests\TestCase;
use PHPUnit\Framework\TestCase;
use Contao\System;

use Psr\Log\NullLogger;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * ModuleBotDetection test case.
 */
class ModuleBotDetectionTest extends TestCase
{

    /**
     *
     * @var ModuleBotDetection
     */
    private $moduleBotDetection;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp() : void
    {
        parent::setUp();
                
        if (!defined('TL_ERROR')) { \define('TL_ERROR', 'ERROR'); }
        if (!defined('TL_ROOT'))  { \define('TL_ROOT', ''); }
        
        $container = new ContainerBuilder();
        $container->set('monolog.logger.contao', new NullLogger());
        $container->set('monolog.logger.contao.files', new NullLogger());
        $container->setParameter('kernel.cache_dir', 'tests/cache');
        $container->setParameter('kernel.project_dir', '.');
        $container->setParameter('kernel.debug', false);
        $container->set('request_stack', new RequestStack());
        System::setContainer($container);
        
        $this->moduleBotDetection = new ModuleBotDetection(/* parameters */);

        CheckBotIp::setBotIpv4List(__DIR__ . '/../src/Resources/contao/config/bot-ip-list-ipv4.txt');
        CheckBotIp::setBotIpv6List(__DIR__ . '/../src/Resources/contao/config/bot-ip-list-ipv6.txt');
        CheckBotIp::setBot_bing_json(__DIR__ . '/../src/Resources/contao/config/bingbot.json');
        CheckBotIp::setBot_google_json(__DIR__ . '/../src/Resources/contao/config/googlebot.json');
        CheckBotIp::setBot_gpt_json(__DIR__ . '/../src/Resources/contao/config/gptbot.json');

        CheckCloudIp::setCloud_aws_json(__DIR__ . '/../src/Resources/contao/config/cloud_aws.json');
        CheckCloudIp::setCloud_azure_json(__DIR__ . '/../src/Resources/contao/config/cloud_azure.json');
        CheckCloudIp::setCloud_google_json(__DIR__ . '/../src/Resources/contao/config/cloud_google.json');
        CheckCloudIp::setCloud_oracle_json(__DIR__ . '/../src/Resources/contao/config/cloud_oracle.json');
        CheckCloudIp::setCloud_hetzner_json(__DIR__ . '/../src/Resources/contao/config/hetzner-cloud.json');
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown() : void
    {
        $this->moduleBotDetection = null;
        
        parent::tearDown();
    }

    /**
     * Tests ModuleBotDetection->getVersion()
     */
    public function testGetVersion()
    {
        $actual = $this->moduleBotDetection->getVersion(/* parameters */);
        $this->assertSame('1.14.2', $actual);
    }

    /**
     * Tests ModuleBotDetection->checkBotAllTests()
     * 
     * @dataProvider useragentBotsProvider
     */
    public function testCheckBotAllTestsBot(bool $result, string $useragent)
    {
        \Contao\Environment::set('requestMethod','GET');
        \Contao\Environment::set('ip','127.0.1.1');       
        $actual = $this->moduleBotDetection->checkBotAllTests($useragent);
        $this->assertSame($result, $actual);
    }
    public function useragentBotsProvider()
    {
        return [
            [true, 'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)'],
            [true, 'ia_archiver (+http://www.alexa.com/site/help/webmasters; crawler@alexa.com)'],
            [true, 'Yandex/1.01.001 (compatible; Win16; P)'],
            [true, 'ia_archiver-web.archive.org'],
            [true, 'HubSpot Connect 1.0 (http://dev.hubspot.com/)'],
            [true, 'python-requests/1.2.0 CPython/2.7.3 Linux/3.2.0-41-virtual'],
            [true, 'check_http/v2.2 (monitoring-plugins 2.2)'],
            [true, 'Mozilla/5.0 (compatible; phpservermon/3.2.2; +http://www.phpservermonitor.org)'],
            [true, 'CFNetwork/'],
            [true, 'Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko PTST/276'],
            [true, 'Mozilla/5.0 (compatible; WebCookies/1.0; +https://webcookies.org/faq/#agent)'],
            [true, 'Chrome Privacy Preserving Prefetch Proxy'],
            [true, 'cpp-httplib/0.10.9'],
            [true, 'Mozilla/5.0 AppleWebKit/537.36 (KHTML, like Gecko; compatible; GPTBot/1.0; +https://openai.com/gptbot)'],
            [true, 'Mozilla/5.0 AppleWebKit/537.36 (KHTML, like Gecko); compatible; ChatGPT-User/1.0; +https://openai.com/bot']
        ];
    }

    /**
     * Tests ModuleBotDetection->checkBotAllTests()
     * 
     * @dataProvider useragentBrowserProvider
     */
    public function testCheckBotAllTestsBrowser(string $useragent)
    {
        \Contao\Environment::set('requestMethod','GET');
        \Contao\Environment::set('ip','127.0.1.1');
        $actual = $this->moduleBotDetection->checkBotAllTests($useragent);
        $this->assertFalse($actual, $useragent);
    }
    public function useragentBrowserProvider()
    {
        return [
            ['Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3'],
            ['Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.2; Trident/4.0; Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1);'],
            ['Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_3; de-de) AppleWebKit/531.22.7 (KHTML, like Gecko) Version/4.0.5 Safari/531.22.7'],
            ['Mozilla/5.0 (Linux; Android 6.0; IDbot553 Build/MRA58K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/59.0.3071.125 Mobile Safari/537.36'],
            ['Mozilla/5.0 (Linux; Android 6.0; B BOT 550 Build/MRA58K; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/53.0.2785.124 Mobile Safari/537.36'],
            ['Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.36 Edg/118.0.2088.69']
            //['T-Online Browser']
        ];
    }

    /**
     * Tests Browsers via CheckBotAgentSimple::checkAgent()
     * 
     * @dataProvider useragentBrowserSimpleProvider
     */
    public function testCheckBrowserSimple(string $useragent)
    {
        \Contao\Environment::set('requestMethod','GET');
        \Contao\Environment::set('ip','127.0.1.1');
        $actual = BugBuster\BotDetection\CheckBotAgentSimple::checkAgent($useragent);
        $this->assertFalse($actual, $useragent);
    }
    public function useragentBrowserSimpleProvider()
    {
        return [
            ['Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3'],
            ['Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.2; Trident/4.0; Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1);'],
            ['Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_3; de-de) AppleWebKit/531.22.7 (KHTML, like Gecko) Version/4.0.5 Safari/531.22.7'],
            ['T-Online Browser'],
            ['Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.36 Edg/118.0.2088.69']
        ];
    }

    
    /**
     * Tests ModuleBotDetection->checkBotAllTests()
     */
    public function testCheckBotAllTestsIp()
    {
        // weitere über CheckBotIpTest.php
        BugBuster\BotDetection\CheckBotIp::setBotIpv4List(__DIR__ . '/../src/Resources/contao/config/bot-ip-list-ipv4.txt');
        $actual = BugBuster\BotDetection\CheckBotIp::checkIP('66.249.79.99');
        $this->assertSame(true, $actual);
    }
    

    /**
     * Tests ModuleBotDetection->checkGetPostRequest()
     */
    public function testCheckGetPostRequest()
    {
        \Contao\Environment::set('requestMethod','GET');
        $actual = $this->moduleBotDetection->checkGetPostRequest(/* parameters */);
        $this->assertSame(true, $actual);
    }

    /**
     * Tests CheckBotAgentExtended::checkAgentViaCrawlerDetect
     * 
     * @dataProvider useragentCrawlerDetectProvider
     */
    public function testCheckCrawlerDetectCrawlers(string $useragent)
    {
        $actual =  BugBuster\BotDetection\CheckBotAgentExtended::checkAgentViaCrawlerDetect($useragent);
        $this->assertTrue($actual, $useragent);
    }
    public function useragentCrawlerDetectProvider()
    {
        $arrCrawlers =[];
        $lines = file(__DIR__ . '/../src/Functional/crawlers.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $i=0;
        foreach ($lines as $line) 
        {
            $arrCrawlers[] = [$line];
            if (defined('UNITTEST_SHORT')) {
                $i++;
                if ($i >100) { 
                    fwrite(STDOUT, 'UNITTEST SHORT in CheckCrawlerDetectCrawlers!' . "\n");
                    break; 
                }
            }
        }
        return $arrCrawlers;
    }

    /**
     * Tests BotDetection->checkBotAllTests with CrawlerDetect Devices
     * 
     * @dataProvider useragentCrawlerDetectDeviceProvider
     */
    public function testCheckCrawlerDetectDevices(string $useragent)
    {
        $actual = $this->moduleBotDetection->checkBotAllTests($useragent);
        $this->assertFalse($actual, $useragent);
    }
    public function useragentCrawlerDetectDeviceProvider()
    {
        $arrDevices =[];
        $lines = file(__DIR__ . '/../src/Functional/devices.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $i=0;
        foreach ($lines as $line) 
        {
            if (strstr($line,'CFNetwork')) { continue; }
            $arrDevices[] = [$line];
            if (defined('UNITTEST_SHORT')) {
                $i++;
                if ($i >100) { 
                    fwrite(STDOUT, 'UNITTEST SHORT in CheckCrawlerDetectDevices!' . "\n");
                    break; 
                }
            }
        }
        return $arrDevices;
    }

}

