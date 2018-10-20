<?php
/**
 * Contao Open Source CMS, Copyright (C) 2005-2017 Leo Feyer
*
* Modul BotDetection - runonce
*
* PHP version 5
* @copyright  Glen Langer 2007..2017
* @author     Glen Langer
* @package    BotDetection
* @license    LGPL
*/
namespace BugBuster\BotDetection;

use BugBuster\BotDetection\Referrer\ProviderCommunication;
use BugBuster\BotDetection\Referrer\ProviderParser;

/**
 * Class BotDetectionRunonceJobDel6006
 *
 * @copyright  Glen Langer 2015..2917
 * @author     Glen Langer
 * @package    Banner
 * @license    LGPL
 */
class BotDetectionRunonceJobDel6006 extends \Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function run()
    {
        // Prefill the cache
        $referrerProvider = array();
        include_once(TL_ROOT . '/vendor/bugbuster/contao-botdetection-bundle/src/Resources/contao/config/referrer-provider.php');
        $proCom = new ProviderCommunication($referrerProvider, false);
        
        if (true === $proCom->loadProviderFiles())
        {
            $cachePath = $proCom->getCachePath();
        }
        if (false !== $cachePath)
        {
            $proPar = new ProviderParser($referrerProvider, $cachePath);
        
            if ( true === $proPar->isUpdateProviderListNecessary() &&
                 true === $proPar->generateProviderList()
                )
            {
                $proPar->cleanProviderList();
                $proPar->writeProviderList();
            }
        }
        
        // delete old cache/largebrowscap_v6006_1.0.4/ directory
        if (is_dir(TL_ROOT . '/vendor/bugbuster/contao-botdetection-bundle/src/Resources/contao/cache/largebrowscap_v6006_1.0.4'))
        {
            $folder = new \Folder('vendor/bugbuster/contao-botdetection-bundle/src/Resources/contao/cache/largebrowscap_v6006_1.0.4');
            if (!$folder->isEmpty())
            {
                $folder->purge();
            }
            $folder->delete();
            $folder=null;
            unset($folder);
        }
        // delete old cache/largebrowscap_v6008_1.0.4/ directory
        if (is_dir(TL_ROOT . '/vendor/bugbuster/contao-botdetection-bundle/src/Resources/contao/cache/largebrowscap_v6008_1.0.4'))
        {
            $folder = new \Folder('vendor/bugbuster/contao-botdetection-bundle/src/Resources/contao/cache/largebrowscap_v6008_1.0.4');
            if (!$folder->isEmpty())
            {
                $folder->purge();
            }
            $folder->delete();
            $folder=null;
            unset($folder);
        }
        // delete old cache/largebrowscap_v6015_1.0.4/ directory
        if (is_dir(TL_ROOT . '/vendor/bugbuster/contao-botdetection-bundle/src/Resources/contao/cache/largebrowscap_v6015_1.0.4'))
        {
            $folder = new \Folder('vendor/bugbuster/contao-botdetection-bundle/src/Resources/contao/cache/largebrowscap_v6015_1.0.4');
            if (!$folder->isEmpty())
            {
                $folder->purge();
            }
            $folder->delete();
            $folder=null;
            unset($folder);
        }
        // delete old cache/largebrowscap_v6021_1.0.5/ directory
        if (is_dir(TL_ROOT . '/vendor/bugbuster/contao-botdetection-bundle/src/Resources/contao/cache/largebrowscap_v6021_1.0.5'))
        {
            $folder = new \Folder('vendor/bugbuster/contao-botdetection-bundle/src/Resources/contao/cache/largebrowscap_v6021_1.0.5');
            if (!$folder->isEmpty())
            {
                $folder->purge();
            }
            $folder->delete();
            $folder=null;
            unset($folder);
        }
        // delete old cache/largebrowscap_v6026_1.0.5/ directory
        if (is_dir(TL_ROOT . '/vendor/bugbuster/contao-botdetection-bundle/src/Resources/contao/cache/largebrowscap_v6026_1.0.5'))
        {
            $folder = new \Folder('vendor/bugbuster/contao-botdetection-bundle/src/Resources/contao/cache/largebrowscap_v6026_1.0.5');
            if (!$folder->isEmpty())
            {
                $folder->purge();
            }
            $folder->delete();
            $folder=null;
            unset($folder);
        }
        
    }
}

$objBotDetectionRunonceJobDel6006 = new BotDetectionRunonceJobDel6006();
$objBotDetectionRunonceJobDel6006->run();
