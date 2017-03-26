<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2017 Leo Feyer
 *
 * @license LGPL-3.0+
 */


/**
 * Register the namespaces
 */
ClassLoader::addNamespaces(array
(
	'BugBuster',
));


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	// Modules
	'BugBuster\BotDetection\ModuleBotDetection'     => 'vendor/bugbuster/contao-botdetection-bundle/src/Resources/contao/modules/ModuleBotDetection.php',
	'BugBuster\BotDetection\ModuleBrowscapCache'    => 'vendor/bugbuster/contao-botdetection-bundle/src/Resources/contao/modules/ModuleBrowscapCache.php',
	'BugBuster\BotDetection\ModuleFrontendDemo1'    => 'vendor/bugbuster/contao-botdetection-bundle/src/Resources/contao/modules/ModuleFrontendDemo1.php',
	'BugBuster\BotDetection\ModuleFrontendDemo2'    => 'vendor/bugbuster/contao-botdetection-bundle/src/Resources/contao/modules/ModuleFrontendDemo2.php',

	// Classes
	'BugBuster\BotDetection\CheckBotAgentExtended' => 'vendor/bugbuster/contao-botdetection-bundle/src/Resources/contao/classes/CheckBotAgentExtended.php',
	'BugBuster\BotDetection\CheckBotAgentSimple'   => 'vendor/bugbuster/contao-botdetection-bundle/src/Resources/contao/classes/CheckBotAgentSimple.php',
	'BugBuster\BotDetection\BrowscapCache'         => 'vendor/bugbuster/contao-botdetection-bundle/src/Resources/contao/classes/BrowscapCache.php',
	'BugBuster\BotDetection\CheckBotReferrer'      => 'vendor/bugbuster/contao-botdetection-bundle/src/Resources/contao/classes/CheckBotReferrer.php',
	'BugBuster\BotDetection\CheckBotIp'            => 'vendor/bugbuster/contao-botdetection-bundle/src/Resources/contao/classes/CheckBotIp.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'mod_botdetection_browscap_fe' => 'vendor/bugbuster/contao-botdetection-bundle/src/Resources/contao/templates',
	'mod_botdetection_demo2_fe'    => 'vendor/bugbuster/contao-botdetection-bundle/src/Resources/contao/templates',
	'mod_botdetection_demo1_fe'    => 'vendor/bugbuster/contao-botdetection-bundle/src/Resources/contao/templates',
));
