<?php

use BugBuster\BotDetection\Referrer\ProviderParser;
use PHPUnit\Framework\TestCase;

/**
 * ProviderParser test case.
 */
class ProviderParserTest extends TestCase
{

    /**
     *
     * @var ProviderParser
     */
    private $providerParser;
    
    private $referrerProvider;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
        
        // TODO Auto-generated ProviderParserTest::setUp()
        $this->referrerProvider = [
            'Stevie_Ray'    => 'https://raw.githubusercontent.com/Stevie-Ray/referrer-spam-blocker/master/src/domains.txt',
            'Flameeyes'     => 'https://raw.githubusercontent.com/Flameeyes/modsec-flameeyes/master/rules/flameeyes_bad_referrers.data',
            'desbma'        => 'https://raw.githubusercontent.com/desbma/referer-spam-domains-blacklist/master/spammers.txt'
        ];
        $this->providerParser = new ProviderParser($this->referrerProvider, 'tests/cache');
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        // TODO Auto-generated ProviderParserTest::tearDown()
        $this->providerParser = null;
        
        parent::tearDown();
    }


    /**
     * Tests ProviderParser->generateProviderList()
     */
    public function testGenerateProviderList()
    {
        $ret = $this->providerParser->generateProviderList(/* parameters */);
        $this->assertTrue($ret);
    }

    /**
     * Tests ProviderParser->cleanProviderList()
     */
    public function testCleanProviderList()
    {
        $this->providerParser->generateProviderList(/* parameters */);
        
        $raw = count($this->providerParser->getReferrerSpammer());
        
        $this->providerParser->cleanProviderList(/* parameters */);
        
        $clean = count($this->providerParser->getReferrerSpammer());
        
        $this->assertTrue( ($raw > $clean) );
        
    }
    
    /**
     * Tests ProviderParser->writeProviderList()
     */
    public function testWriteProviderList()
    {
        $this->providerParser->generateProviderList(/* parameters */);
        $this->providerParser->cleanProviderList(/* parameters */);
        $ret = $this->providerParser->writeProviderList(/* parameters */);
        $this->assertTrue($ret);
    
    }
}
