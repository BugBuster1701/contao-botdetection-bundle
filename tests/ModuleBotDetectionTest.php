<?php


use BugBuster\BotDetection\ModuleBotDetection;
//use Contao\CoreBundle\Tests\TestCase;
use PHPUnit\Framework\TestCase;
use Contao\System;

use Psr\Log\NullLogger;
use Symfony\Component\DependencyInjection\ContainerBuilder;

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
    protected function setUp()
    {
        parent::setUp();
                
        if (!defined('TL_ERROR')) { \define('TL_ERROR', 'ERROR'); }
        if (!defined('TL_ROOT'))  { \define('TL_ROOT', ''); }
        
        $container = new ContainerBuilder();
        $container->set('monolog.logger.contao', new NullLogger());
        $container->setParameter('kernel.cache_dir', 'tests/cache');
        $container->setParameter('kernel.project_dir', '.');
        System::setContainer($container);
        
        $this->moduleBotDetection = new ModuleBotDetection(/* parameters */);
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
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
        $this->assertSame('1.5.2', $actual);
    }

    /**
     * Tests ModuleBotDetection->checkBotAllTests()
     * 
     * @dataProvider useragentBotsProvider
     */
    public function testCheckBotAllTestsBot(bool $result, string $useragent)
    {
        \Environment::set('requestMethod','GET');
        \Environment::set('ip','127.0.1.1');       
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
            [true, 'CFNetwork/']
        ];
    }
    
    /**
     * Tests ModuleBotDetection->checkBotAllTests()
     * 
     * @dataProvider useragentBrowserProvider
     */
    public function testCheckBotAllTestsBrowser(bool $result, string $useragent)
    {
        \Environment::set('requestMethod','GET');
        \Environment::set('ip','127.0.1.1');
        $actual = $this->moduleBotDetection->checkBotAllTests($useragent);
        $this->assertSame($result, $actual);
    }
    public function useragentBrowserProvider()
    {
        return [
            [false, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3'],
            [false, 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.2; Trident/4.0; Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1);'],
            [false, 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_3; de-de) AppleWebKit/531.22.7 (KHTML, like Gecko) Version/4.0.5 Safari/531.22.7']
        ];
    }
    
    /**
     * Tests ModuleBotDetection->checkBotAllTests()
     */
    public function testCheckBotAllTestsIp()
    {
        // weitere über CheckBotIpTest.php
        BugBuster\BotDetection\CheckBotIp::setBotIpv4List(__DIR__ . '/../src/Resources/contao/config/bot-ip-list-ipv4.txt');
        $actual = BugBuster\BotDetection\CheckBotIp::checkIP('66.249.95.222');
        $this->assertSame(true, $actual);
    }
    

    /**
     * Tests ModuleBotDetection->checkGetPostRequest()
     */
    public function testCheckGetPostRequest()
    {
        \Environment::set('requestMethod','GET');
        $actual = $this->moduleBotDetection->checkGetPostRequest(/* parameters */);
        $this->assertSame(true, $actual);
    }
}

