<?php

use BugBuster\BotDetection\CheckBotIp;
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
     * Tests CheckBotIp::checkIP()
     * 
     * @dataProvider ipProvider
     */
    public function testCheckIp(bool $result, string $ip)
    {
        $actual = CheckBotIp::checkIP($ip);
        $this->assertSame($result, $actual);
    }
    public function ipProvider()
    {
        return [
            [false,'192.168.17.01'],    //Private IP
            [false,'74.125.79.100'],    //Google Plus
            [true ,'192.114.71.13'],    //web spider israel
            [true ,'65.55.231.74'],     //in 65.52.0.0/14 - MSN Net
            [true ,'66.249.95.222'],    //in 66.249.64.0/19 - Google Net
            [true ,'2001:4860:4801:1109:0:6006:1300:b075'],     //Google Bot IPv6
            [false,'2001:0db8:85a3:08d3:1319:8a2e:0370:7334'],  //No Bot
            [false,'::ffff:c000:280'],      //double quad notation for ipv4 mapped addresses
            [false,'::ffff:192.0.2.128']    //double quad notation for ipv4 mapped addresses
        ];
    }
}

