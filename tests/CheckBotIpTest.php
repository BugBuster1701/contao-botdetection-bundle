<?php

use BugBuster\BotDetection\CheckBotIp;
use BugBuster\BotDetection\CheckCloudIp;
use PHPUnit\Framework\TestCase;

/**
 * CheckBotIp test case.
 */
class CheckBotIpTest extends TestCase
{
    /**
     * Prepares the environment before running a test.
     */
    protected function setUp() : void
    {
        parent::setUp();
        
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
        parent::tearDown();
    }

    /**
     * Tests CheckBotIp::getBotIpv4List()
     */
    public function testGetBotIpv4List()
    {
        $actual = CheckBotIp::getBotIpv4List(/* parameters */);
        $this->assertSame(__DIR__ . '/../src/Resources/contao/config/bot-ip-list-ipv4.txt', $actual);
    }

    /**
     * Tests CheckBotIp::getBotIpv6List()
     */
    public function testGetBotIpv6List()
    {
        $actual = CheckBotIp::getBotIpv6List(/* parameters */);
        $this->assertSame(__DIR__ . '/../src/Resources/contao/config/bot-ip-list-ipv6.txt', $actual);
    }

    /**
     * Tests CheckBotIp::getBot_bing_json
     */
    public function testGetBotBingJson()
    {
        $actual = CheckBotIp::getBot_bing_json(/* parameters */);
        $this->assertSame(__DIR__ . '/../src/Resources/contao/config/bingbot.json', $actual);
    }

    /**
     * Tests CheckBotIp::getBot_google_json
     */
    public function testGetBotGoogleJson()
    {
        $actual = CheckBotIp::getBot_google_json(/* parameters */);
        $this->assertSame(__DIR__ . '/../src/Resources/contao/config/googlebot.json', $actual);
    }

    /**
     * Test CheckBotIp::getBot_gpt_json
     */
    public function testGetBotGptJson()
    {
        $actual = CheckBotIp::getBot_gpt_json(/* parameters */);
        $this->assertSame(__DIR__ . '/../src/Resources/contao/config/gptbot.json', $actual);
    }

    /**
     * Tests CheckBotIp::checkIP()
     * 
     * @dataProvider ipProvider
     */
    public function testCheckIp(bool $result, string $ip)
    {
        $actual = CheckBotIp::checkIP($ip);
        $this->assertSame($result, $actual, $ip);
    }
    public function ipProvider()
    {
        return [
            [false,'192.168.17.01'],    //Private IP
            [true ,'34.100.182.99'],    //Google
            [true ,'192.114.71.13'],    //web spider israel
            [true ,'65.55.231.74'],     //in 65.52.0.0/14 - MSN Net
            [true ,'66.249.78.22'],    //in 66.249.78.0/27 - Google
            [true ,'2001:4860:4801:1109:0:6006:1300:b075'],     //Google Bot IPv6
            [true ,'2001:4860:4801:1c:1111:2222:3333:4444'],    //Google Bot IPv6
            [false,'2001:0db8:85a3:08d3:1319:8a2e:0370:7334'],  //No Bot
            [false,'::ffff:c000:280'],      //double quad notation for ipv4 mapped addresses 192.0.2.128
            [false,'::ffff:192.0.2.128'],   //double quad notation for ipv4 mapped addresses 192.0.2.128
            [true ,'40.77.167.11'],                             // Bing Bot, offizielle Liste
            [true ,'66.249.70.11'],                             // Google Bot, offizielle Liste
            [true ,'2001:4860:4801:000f:0000:0000:0000:8888'],  // Google Bot, offizielle Liste
            [true ,'52.230.152.11']                             // GPT Bot, offizielle Liste
        ];
    }

    /**
     * Tests CheckCloudIp::checkIP()
     * 
     * @dataProvider ipCloudProvider
     */
    public function testCheckCloudIp(bool $result, string $ip)
    {
        $actual = CheckCloudIp::checkIP($ip);
        $this->assertSame($result, $actual, $ip);
    }
    public function ipCloudProvider()
    {
        return [
            [true ,'52.230.152.11'],                            // GPT Bot, offizielle Liste
            [true ,'3.5.140.11'],                               // Cloud AWS, offizielle Liste
            [true ,'2a05:d034:8000:0000:0000:0000:0000:0001'],  // Cloud AWS, offizielle Liste
            [true ,'13.70.74.112'],                             // Cloud AzureBotService, offizielle Liste
            [true ,'2603:1030:0010:0001:0000:0000:0000:0020'],  // Cloud AzureBotService, offizielle Liste
            [true ,'4.149.0.0'],                                // Cloud Azure, offizielle Liste
            [true ,'2a01:0111:f403:e01a:0000:0000:0000:8888'],  // Cloud Azure, offizielle Liste
            [true ,'104.199.244.11'],                           // Cloud Google, offizielle Liste
            [true ,'2600:1900:4180:0000:0000:0000:0000:8888'],  // Cloud Google, offizielle Liste
            [true, '129.151.48.11'],                            // Cloud Oracle, offizielle Liste
            [true, '159.69.1.1']                                // Cloud Hetzner, manuell erstellt ipinfo.io
        ];
    }

    /**
     * Tests CheckBotIp::getUserIP()
     */
    public function testGetUserIp()
    {
        $actual = CheckBotIp::getUserIP();
        $this->assertFalse($actual, 'no variable $_SERVER is set');
    }

    /**
     * Tests CheckBotIp::getUserIP() via private IP
     */
    public function testGetUserIpPrivate()
    {
        $_SERVER['REMOTE_ADDR'] = '192.168.17.1';
        global $_SERVER;
        $actual = CheckBotIp::getUserIP();
        $this->assertNotFalse($actual,$_SERVER['REMOTE_ADDR']);
    }

    /**
     * Tests CheckBotIp::getUserIP() via reserved address
     */
    public function testGetUserIpReservedAddress()
    {
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
        global $_SERVER;
        $actual = CheckBotIp::getUserIP();
        $this->assertNotFalse($actual,$_SERVER['REMOTE_ADDR']);
    }

    /**
     * Tests CheckBotIp::getUserIP() via private addresses
     */
    public function testGetUserIpPrivateAddresses()
    {
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1, 192.168.17.1';
        global $_SERVER;
        $actual = CheckBotIp::getUserIP();
        $this->assertNotFalse($actual,$_SERVER['REMOTE_ADDR']);
    }

    /**
     * Tests CheckBotIp::getUserIP() via invalid address
     */
    public function testGetUserIpInvalidAddress()
    {
        $_SERVER['REMOTE_ADDR'] = '256.1.2.3';
        global $_SERVER;
        $actual = CheckBotIp::getUserIP();
        $this->assertFalse($actual,$_SERVER['REMOTE_ADDR']);
    }

    /**
     * Tests CheckBotIp::getUserIP() via invalid addresses, last is ok
     */
    public function testGetUserIpInvalidAddresses()
    {
        $_SERVER['REMOTE_ADDR'] = '256.1.2.3, 192.168.17.1';
        global $_SERVER;
        $actual = CheckBotIp::getUserIP();
        $this->assertNotFalse($actual,$_SERVER['REMOTE_ADDR']);
    }
}

