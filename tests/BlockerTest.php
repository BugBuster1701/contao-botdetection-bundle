<?php

/*
 * This file is part of a BugBuster Contao Bundle
 *
 * @copyright  Glen Langer 2018 <http://contao.ninja>
 * @author     Glen Langer (BugBuster)
 * @package    Contao BotDetection Bundle
 * @license LGPL-3.0-or-later
 * @see	    https://github.com/BugBuster1701/contao-botdetection-bundle
 */

use BugBuster\BotDetection\Referrer\Blocker;
use PHPUnit\Framework\TestCase;

/**
 * Blocker test case.
 */
class BlockerTest extends TestCase
{

    /**
     *
     * @var Blocker
     */
    private $blocker;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp() : void
    {
        parent::setUp();
        
        $this->blocker = new Blocker('acme.com', 'tests/cache');
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown() : void
    {
        $this->blocker = null;
        
        parent::tearDown();
    }


    /**
     * Tests Blocker->getCachePath()
     */
    public function testGetCachePath()
    {
        $actual = $this->blocker->getCachePath(/* parameters */);
        $this->assertEquals('tests/cache', $actual);
    }

    /**
     * Tests Blocker->getReferrer()
     */
    public function testGetReferrer()
    {
        $actual = $this->blocker->getReferrer(/* parameters */);
        $this->assertEquals('acme.com', $actual);
    }

    /**
     * Tests Blocker->setCachePath()
     */
    public function testSetCachePath()
    {
        $this->blocker->setCachePath('foo/bar/baz');
        $actual = $this->blocker->getCachePath(/* parameters */);
        $this->assertEquals('foo/bar/baz', $actual);        
    }

    /**
     * Tests Blocker->setReferrer()
     */
    public function testSetReferrer()
    {
        $this->blocker->setReferrer('localhost.lan');
        $actual = $this->blocker->getReferrer(/* parameters */);
        $this->assertEquals('localhost.lan', $actual);
    }

    /**
     * Tests Blocker->isReferrerSpam()
     * 
     * @dataProvider providerSpam
     */
    public function testIsReferrerSpam($result, $referrer)
    {
        $this->blocker->setReferrer($referrer);
        
        $referrerlist = $this->blocker->getCachePath() .'/referrerblocked.txt';
        $blocklist    = file_get_contents($referrerlist);
        $actual = $this->blocker->isReferrerSpam($blocklist);
        
        $this->assertTrue($actual == $result);
    }
    public function providerSpam()
    {
        return array(//result,host
            array(true , 'wdfdocando.com'),
            array(true , 'backgroundpictures.net'),
            array(false, 'web-list'),
            array(false, 'web-list.de'),
            array(false, 'contao.org')
        );
    }
}

