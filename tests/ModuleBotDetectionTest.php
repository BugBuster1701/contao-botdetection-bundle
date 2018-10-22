<?php


use BugBuster\BotDetection\ModuleBotDetection;
use Contao\CoreBundle\Tests\TestCase;
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
        $container->setParameter('kernel.project_dir', '');
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
        $this->assertSame('1.5.0', $actual);
    }

    /**
     * Tests ModuleBotDetection->checkBotAllTests()
     */
    public function testCheckBotAllTests()
    {
        // TODO weitere über dataProvider
       
        $actual = $this->moduleBotDetection->checkBotAllTests('Mozilla/4.0 (compatible; Blog Search;)');
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

