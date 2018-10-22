<?php

declare(strict_types=1);

/**
 * Contao Open Source CMS, Copyright (C) 2005-2017 Leo Feyer
 *
 * Modul BotDetection - Frontend Demo
 * 
 * @copyright  Glen Langer 2007..2017 <http://contao.ninja>
 * @author     Glen Langer (BugBuster)
 * @license    LGPL
 * @filesource
 * @see        https://github.com/BugBuster1701/contao-botdetection-bundle 
 */

/**
 * Run in a custom namespace, so the class can be replaced
 */

namespace BugBuster\BotDetection;

/**
 * Class ModuleFrontendDemo1
 * Use ModuleBotDetection with import function
 *
 * @copyright  Glen Langer 2007..2017 <http://contao.ninja>
 * @author     Glen Langer (BugBuster)
 */
class ModuleFrontendDemo1 extends \Module
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_botdetection_demo1_fe';

	/**
	 * TL_ROOT over Container
	 *
	 * @var string
	 */
	private $rootDir;

	private $ModuleBotDetection;

	const BOT_IP4_LIST          = "/vendor/bugbuster/contao-botdetection-bundle/src/Resources/contao/config/bot-ip-list-ipv4.txt";
	const BOT_IP6_LIST          = "/vendor/bugbuster/contao-botdetection-bundle/src/Resources/contao/config/bot-ip-list-ipv6.txt";

	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new \BackendTemplate('be_wildcard');
			$objTemplate->wildcard = '### Bot Detection Frontend Demo 1 ###';

			$objTemplate->title = $this->headline;
            $objTemplate->id = $this->id;
            $objTemplate->link = $this->name;

            $objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

            return $objTemplate->parse();
		}

		return parent::generate();
	}

	/**
	 * Generate module
	 */
	protected function compile()
	{
	    $this->rootDir = \System::getContainer()->getParameter('kernel.project_dir');
	    //einzel tests direkt aufgerufen
	    $test01 = CheckBotAgentSimple::checkAgent(\Environment::get('httpUserAgent')); // own Browser
	    CheckBotIp::setBotIpv4List($this->rootDir . self::BOT_IP4_LIST);
	    CheckBotIp::setBotIpv6List($this->rootDir . self::BOT_IP6_LIST);
	    $test02 = CheckBotIp::checkIP(\Environment::get('ip')); // own IP
	    $test03 = CheckBotAgentExtended::checkAgent(\Environment::get('httpUserAgent')); // own Browser

	    //for fe template
	    $arrDemo[] = array(
	       'type'          => 'agent',
	       'test'          => '01',
	       'theoretical'   => 'false',
	       'actual'        => var_export($test01, true),
	       'comment'       => '<br />'.\Environment::get('httpUserAgent'),
	       'color'         => ($test01 === false) ? 'green' : 'red'
	    );
	    $arrDemo[] = array(
	       'type'          => 'ip',
	       'test'          => '02',
	       'theoretical'   => 'false',
	       'actual'        => var_export($test02, true),
	       'comment'       => '<br />'.\Environment::get('ip'),
	       'color'         => ($test02 === false) ? 'green' : 'red'
	    );
	    $arrDemo[] = array(
	       'type'          => 'agentadvanced',
	       'test'          => '03',
	       'theoretical'   => 'false',
	       'actual'        => var_export($test03, true),
	       'comment'       => '<br />'.\Environment::get('httpUserAgent'),
	       'color'         => ((bool) $test03 === false) ? 'green' : 'red'
	    );	    

	    //Gesamt Test Aufruf
	    $this->ModuleBotDetection = new ModuleBotDetection();
	    $test04 = $this->ModuleBotDetection->checkBotAllTests(\Environment::get('httpUserAgent'));

	    $arrDemo[] = array(
	        'type'          => 'alltests',
	        'test'          => '04',
	        'theoretical'   => 'false',
	        'actual'        => var_export($test04, true),
	        'comment'       => '<br />'.\Environment::get('httpUserAgent'),
	        'color'         => ($test04 === false) ? 'green' : 'red'
	    );
	    $this->Template->demos = $arrDemo;

	    // get module version
	    $this->Template->version = $this->ModuleBotDetection->getVersion();
	}

}

