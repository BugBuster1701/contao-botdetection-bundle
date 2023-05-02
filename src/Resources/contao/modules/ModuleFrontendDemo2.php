<?php

declare(strict_types=1);

/*
 * This file is part of a BugBuster Contao Bundle (Resources\contao)
 *
 * @copyright  Glen Langer 2020 <http://contao.ninja>
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
 * Class ModuleFrontendDemo2
 * Use ModuleBotDetection with import function
 *
 * @copyright  Glen Langer 2007..2020 <http://contao.ninja>
 * @author     Glen Langer (BugBuster)
 */
class ModuleFrontendDemo2 extends \Contao\Module
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_botdetection_demo2_fe';

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
			$objTemplate->wildcard = '### Bot Detection Frontend Demo 2 ###';

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
	    $arrFields = array();
	    $arrFields['name'] = array
		(
			'name' => 'name',
			'label' => $GLOBALS['TL_LANG']['MSC']['botdetectiondemo2_agent'],
			'inputType' => 'text',
			'eval' => array('mandatory'=>true, 'maxlength'=>256, 'decodeEntities'=>true)
		);
		$arrFields['captcha'] = array
		(
			'name' => 'captcha',
			'label' => $GLOBALS['TL_LANG']['MSC']['botdetectiondemo2_captcha'],
			'inputType' => 'captcha',
			'eval' => array('mandatory'=>true)
		);

		$doNotSubmit = false;
		$strFormId = 'botdetectiondemo2_' . $this->id;
		$arrWidgets = array();
		$strFields = '';

		// Initialize widgets
		foreach ($arrFields as $arrField)
		{
			/** @var \String $strClass */
			$strClass = $GLOBALS['TL_FFL'][$arrField['inputType']];

			// Continue if the class is not defined
			if (!class_exists($strClass)) { continue; }

			$arrField['eval']['required'] = $arrField['eval']['mandatory'];

			/** @var \Widget $objWidget */
			$objWidget = new $strClass($strClass::getAttributesFromDca($arrField, $arrField['name'], $arrField['value']));

			// Validate widget
			if (\Contao\Input::post('FORM_SUBMIT') == $strFormId)
			{
				$objWidget->validate();

				if ($objWidget->hasErrors())
				{
					$doNotSubmit = true;
				}
			}

			$strFields .= $objWidget->parse();
		}
		$this->Template->formId = $strFormId;
		$this->Template->fields = $strFields;

   		$this->Template->slabel   = $GLOBALS['TL_LANG']['MSC']['botdetectiondemo2_submit'];
		$this->Template->action   = \Contao\StringUtil::ampersand(\Contao\Environment::get('request'));
		$this->Template->hasError = $doNotSubmit;
		$this->Template->requestToken = System::getContainer()->get('contao.csrf.token_manager')->getDefaultTokenValue();

	    if (\Contao\Input::post('FORM_SUBMIT') == $strFormId && !$doNotSubmit)
		{
			$arrSet = array('agent_name' => \Contao\Input::post('name', true));

			//einzel tests direkt aufgerufen
    	    $test01 = CheckBotAgentSimple::checkAgent($arrSet['agent_name']);
    	    $test02 = CheckBotAgentExtended::checkAgentName($arrSet['agent_name']);
    	    $BrowsCapInfo = CheckBotAgentExtended::getBrowscapResult($arrSet['agent_name']);
    	    $not1 = ($test01) ? "<span style=\"color:green;\">".$GLOBALS['TL_LANG']['MSC']['botdetectiondemo2_found']."</span>" : "<span style=\"color:red;\">".$GLOBALS['TL_LANG']['MSC']['botdetectiondemo2_not']."</span>";
    	    $not2 = ($test02) ? "<span style=\"color:green;\">".$GLOBALS['TL_LANG']['MSC']['botdetectiondemo2_found']."</span>" : "<span style=\"color:red;\">".$GLOBALS['TL_LANG']['MSC']['botdetectiondemo2_not']."</span>";
    	    $not3 = ($test02) ? " (".$test02.")" : "";
    	    $messages  = "<strong>".$GLOBALS['TL_LANG']['MSC']['botdetectiondemo2_message_1'].":</strong><br />".$arrSet['agent_name']."<br /><br />";
    	    $messages .= "<div style=\"font-weight:bold; width:200px;float:left;\">CheckBotAgentSimple:</div> ".$not1."<br />";
    	    $messages .= "<div style=\"font-weight:bold; width:200px;float:left;\">CheckBotAgentExtended:</div> ".$not2.$not3."<br />";
    	    $messages .= "<div style=\"font-weight:bold; width:200px;float:left;\">BrowsCapInfo:</div><pre>".print_r($BrowsCapInfo, true)."</pre><br />";

			$this->Template->message  = $messages;

		}
	    // get module version
		$ModuleBotDetection = new ModuleBotDetection('');
	    $this->Template->version = $ModuleBotDetection->getVersion();
	}

}
