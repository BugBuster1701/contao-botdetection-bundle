<?php

declare(strict_types=1);

/*
 * This file is part of a BugBuster Contao Bundle (Resources\contao)
 *
 * @copyright  Glen Langer 2024 <http://contao.ninja>
 * @author     Glen Langer (BugBuster)
 * @package    Contao BotDetection Bundle
 * @license    LGPL-3.0-or-later
 * @see        https://github.com/BugBuster1701/contao-botdetection-bundle
 */

/**
 * Run in a custom namespace, so the class can be replaced
 */

namespace BugBuster\BotDetection;

use Contao\System;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ModuleFrontendDemo1
 * Use ModuleBotDetection with import function
 *
 * @copyright  Glen Langer 2007..2020 <http://contao.ninja>
 * @author     Glen Langer (BugBuster)
 */
class ModuleFrontendDemo1 extends \Contao\Module
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
	const BOT_BING_JSON         = "/vendor/bugbuster/contao-botdetection-bundle/src/Resources/contao/config/bingbot.json";
    const BOT_GOOGLE_JSON       = "/vendor/bugbuster/contao-botdetection-bundle/src/Resources/contao/config/googlebot.json";
    const BOT_GPT_JSON          = "/vendor/bugbuster/contao-botdetection-bundle/src/Resources/contao/config/gptbot.json";

	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (System::getContainer()->get('contao.routing.scope_matcher')
			->isBackendRequest(System::getContainer()->get('request_stack')
			->getCurrentRequest() ?? Request::create('')))
		{
			$objTemplate = new \Contao\BackendTemplate('be_wildcard');
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
	    $this->rootDir = \Contao\System::getContainer()->getParameter('kernel.project_dir');
	    //einzel tests direkt aufgerufen
	    $test01 = CheckBotAgentSimple::checkAgent(\Contao\Environment::get('httpUserAgent')); // own Browser
	    CheckBotIp::setBotIpv4List($this->rootDir . self::BOT_IP4_LIST);
	    CheckBotIp::setBotIpv6List($this->rootDir . self::BOT_IP6_LIST);
		CheckBotIp::setBot_bing_json($this->rootDir . self::BOT_BING_JSON);
        CheckBotIp::setBot_google_json($this->rootDir . self::BOT_GOOGLE_JSON);
        CheckBotIp::setBot_gpt_json($this->rootDir . self::BOT_GPT_JSON);
	    $test02 = CheckBotIp::checkIP(\Contao\Environment::get('ip')); // own IP
	    $test03 = CheckBotAgentExtended::checkAgent(\Contao\Environment::get('httpUserAgent')); // own Browser

	    //for fe template
	    $arrDemo[] = array(
	       'type'          => 'agent',
	       'test'          => '01',
	       'theoretical'   => 'false',
	       'actual'        => var_export($test01, true),
	       'comment'       => '<br />'.\Contao\Environment::get('httpUserAgent'),
	       'color'         => ($test01 === false) ? 'green' : 'red'
	    );
	    $arrDemo[] = array(
	       'type'          => 'ip',
	       'test'          => '02',
	       'theoretical'   => 'false',
	       'actual'        => var_export($test02, true),
	       'comment'       => '<br />'.\Contao\Environment::get('ip'),
	       'color'         => ($test02 === false) ? 'green' : 'red'
	    );
	    $arrDemo[] = array(
	       'type'          => 'agentadvanced',
	       'test'          => '03',
	       'theoretical'   => 'false',
	       'actual'        => var_export($test03, true),
	       'comment'       => '<br />'.\Contao\Environment::get('httpUserAgent'),
	       'color'         => ((bool) $test03 === false) ? 'green' : 'red'
	    );	    

	    //Gesamt Test Aufruf
	    $this->ModuleBotDetection = new ModuleBotDetection();
	    $test04 = $this->ModuleBotDetection->checkBotAllTests(\Contao\Environment::get('httpUserAgent'));

	    $arrDemo[] = array(
	        'type'          => 'alltests',
	        'test'          => '04',
	        'theoretical'   => 'false',
	        'actual'        => var_export($test04, true),
	        'comment'       => '<br />'.\Contao\Environment::get('httpUserAgent'),
	        'color'         => ($test04 === false) ? 'green' : 'red'
	    );
	    $this->Template->demos = $arrDemo;

	    // get module version
	    $this->Template->version = $this->ModuleBotDetection->getVersion();
	}

}

