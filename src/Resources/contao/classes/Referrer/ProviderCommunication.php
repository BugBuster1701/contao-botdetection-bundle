<?php
/**
 * Contao Open Source CMS, Copyright (C) 2005-2017 Leo Feyer
 *
 * BotDetection - ProviderCommunication
 *
 * @copyright  Glen Langer 2007..2017 <http://contao.ninja>
 * @author     Glen Langer (BugBuster)
 * @package    BotDetection
 * @license    LGPL
 * @filesource
 * @see        https://github.com/BugBuster1701/contao-botdetection-bundle
 */

namespace BugBuster\BotDetection\Referrer;


/**
 * Class ProviderCommunication
 *
 * @copyright  Glen Langer 2017 <http://contao.ninja>
 * @author     Glen Langer (BugBuster)
 * @package    BotDetection  
 */
class ProviderCommunication
{

    /**
     * @var array
     */
    private $referrerProvider;
    
    /**
     * Cache Path: absolute path / false -> use Contao cache dir
     * 
     * @var string
     */
    private $cachePath;
    
    /**
     * 
     * @var bool
     */
    private $allowUrlOpen;
    

    /**
     * 
     * @param array     $referrerProvider
     * @param string    $cachePath
     */
    public function __construct($referrerProvider, $cachePath = false) 
    {
        $this->referrerProvider = $referrerProvider;
        $this->cachePath        = $cachePath;
        $this->allowUrlOpen     = (bool) ini_get('allow_url_fopen');
        
        $this->checkCachePath();
        
    }
    
    public function checkCachePath()
    {
        if (false === $this->cachePath)
        {
            //Cache Pfad anlegen sofern nötig
            $strCachePath   = \StringUtil::stripRootDir(\System::getContainer()->getParameter('kernel.cache_dir'));
            $objFolder      = new \Folder($strCachePath . '/botdetection');
            unset($objFolder);
            $this->cachePath = $strCachePath . '/botdetection';
        }
        else
        {
            // Create the folder if it does not exist
            if (!is_dir($this->cachePath))
            {
                $strPath = '';
                $arrChunks = explode('/', $this->cachePath);
        
                // Create the folder
                foreach ($arrChunks as $strFolder)
                {
                    $strPath .= ($strPath ? '/' : '') . $strFolder;
                    $ret = @mkdir($strPath, 0775);
                }
            }
        }
    }
    
    /**
     * Load Referrer Provider Files if
     * - provider files to old
     */
    public function loadProviderFiles()
    {
        $lastWeek = time() - (7 * 24 * 60 * 60);
        
        if (false === $this->allowUrlOpen) 
        {
            return false;
        }
        
        foreach($this->referrerProvider as $source => $url) 
        {
            if ( false === file_exists($this->cachePath .'/'. strtolower($source) . '.txt') || 
                 $lastWeek > filemtime($this->cachePath .'/'. strtolower($source) . '.txt') 
               )
            {
                $fileProvider = fopen($this->cachePath .'/'. strtolower($source) . '.txt', 'wb+');
                fwrite($fileProvider, file_get_contents($url) );
                fclose($fileProvider);
            }
        }
        return true;
    }
    

    public function getCachePath()
    {
        return $this->cachePath;
    }
    
    public function getReferrerProvider()
    {
        return $this->referrerProvider;
    }
    
    public function getAllowUrlOpen()
    {
        return $this->allowUrlOpen;
    }
    
    
}


