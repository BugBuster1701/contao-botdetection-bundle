<?php

use BugBuster\BotDetection\Referrer\ProviderCommunication;
use PHPUnit\Framework\TestCase;

/**
 * ProviderCommunication test case.
 */
class ProviderCommunicationTest extends TestCase
{

    /**
     *
     * @var ProviderCommunication
     */
    private $providerCommunication;

    private $referrerProvider;
    
    /**
     * Prepares the environment before running a test.
     */
    protected function setUp() : void
    {
        parent::setUp();
        
        $this->referrerProvider = [
            'Stevie_Ray'    => 'https://raw.githubusercontent.com/Stevie-Ray/referrer-spam-blocker/master/src/domains.txt',
            'Flameeyes'     => 'https://raw.githubusercontent.com/Flameeyes/modsec-flameeyes/main/rules/flameeyes_bad_referrers.data',
            'desbma'        => 'https://raw.githubusercontent.com/desbma/referer-spam-domains-blacklist/master/spammers.txt'
        ];
        //Cache Verzeichnis löschen
        /*
         unlink('tests/cache/desbma.txt');
         unlink('tests/cache/flameeyes.txt');
         unlink('tests/cache/stevie_ray.txt');
         rmdir('tests/cache');
         */
        $this->providerCommunication = new ProviderCommunication($this->referrerProvider, 'tests/cache');
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown() : void
    {
        // TODO Auto-generated ProviderCommunicationTest::tearDown()
        $this->providerCommunication = null;
        
        parent::tearDown();
    }
    
    /**
     * Tests ProviderCommunication->getReferrerProvider()
     */
    public function testGetReferrerProvider()
    {
        $ret = $this->providerCommunication->getReferrerProvider(/* parameters */);
        
        $this->assertEquals($ret, $this->referrerProvider);
    }
    
    /**
     * Tests ProviderCommunication->getCachePath()
     */
    public function testGetCachePath()
    {
        $ret = $this->providerCommunication->getCachePath(/* parameters */);
        $this->assertEquals($ret, 'tests/cache');
    }
    
    /**
     * Tests ProviderCommunication->getAllowUrlOpen()
     */
    public function testGetAllowUrlOpen()
    {
        $ret = $this->providerCommunication->getAllowUrlOpen(/* parameters */);
        $this->assertTrue($ret);
    }

    /**
     * Tests ProviderCommunication->loadProviderFiles()
     */
    public function testLoadProviderFiles()
    {
        $ret = $this->providerCommunication->loadProviderFiles(/* parameters */);
        $this->assertTrue($ret);
        $this->assertTrue(is_file('tests/cache/desbma.txt'));
        $this->assertTrue(is_file('tests/cache/flameeyes.txt'));
        $this->assertTrue(is_file('tests/cache/stevie_ray.txt'));
    }

    /**
     * Tests ProviderCommunication->getUpdateFromGithub()
     */
    public function testGetUpdateFromGithub()
    {
        // TODO Auto-generated ProviderCommunicationTest->testGetUpdateFromGithub()
        $this->markTestIncomplete("getUpdateFromGithub test not implemented");

        //$this->providerCommunication->getUpdateFromGithub(/* parameters */);
    }


}

