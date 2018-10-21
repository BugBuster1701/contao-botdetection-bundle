<?php

/**
 * Contao Open Source CMS, Copyright (C) 2005-2017 Leo Feyer
 *
 * Modul BotDetection
 *
 * @copyright  Glen Langer 2007..2017 <http://contao.ninja>
 * @author     Glen Langer (BugBuster)
 * @license    LGPL 
 * @filesource
 * @see        https://github.com/BugBuster1701/contao-botdetection-bundle
 */

/**
 * -------------------------------------------------------------------------
 * FRONT END MODULES
 * -------------------------------------------------------------------------
 *
 * List all fontend modules and their class names.
 * 
 *   $GLOBALS['FE_MOD'] = array
 *   (
 *       'group_1' => array
 *       (
 *           'module_1' => 'Contentlass',
 *           'module_2' => 'Contentlass'
 *       )
 *   );
 * 
 * Use function array_insert() to modify an existing CTE array.
 */

array_insert($GLOBALS['FE_MOD'], 4, array
(
    'BotDetectionDemo' => array
    (
      'botdetection1' => 'BugBuster\BotDetection\ModuleFrontendDemo1',
      'botdetection2' => 'BugBuster\BotDetection\ModuleFrontendDemo2',
  	)/*,
    'BotDetectionGenerate' => array
    (
      'browscapcachegenerate' => 'BugBuster\BotDetection\ModuleBrowscapCache',
  	)*/
));
