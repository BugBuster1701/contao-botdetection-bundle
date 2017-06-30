<?php
/**
 * Contao Open Source CMS, Copyright (C) 2005-2017 Leo Feyer
 *
 * BotDetection - ProviderParser
 *
 * @copyright  Glen Langer 2007..2017 <http://contao.ninja>
 * @author     Glen Langer (BugBuster)
 * @package    BotDetection
 * @license    LGPL
 * @filesource
 * @see        https://github.com/BugBuster1701/contao-botdetection-bundle
 */

namespace BugBuster\BotDetection\Referrer;

use TrueBV\Exception\LabelOutOfBoundsException;
use TrueBV\Punycode;


/**
 * Class ProviderParser
 *
 * @copyright  Glen Langer 2017 <http://contao.ninja>
 * @author     Glen Langer (BugBuster)
 * @package    BotDetection  
 */
class ProviderParser
{
    
    /**
     * Cache Path: absolute path / false -> use Contao cache dir
     *
     * @var string
     */
    private $cachePath;
    
    private $arrReferrerSpammer;
    
    /**
     * 
     * @param array     $referrerProvider
     * @param string    $cachePath
     */
    public function __construct($referrerProvider, $cachePath = false)
    {
        $this->referrerProvider    = $referrerProvider;
        $this->cachePath           = $cachePath;
        $this->arrReferrerSpammer  = [];
    }
    
    public function getReferrerSpammer()
    {
        return $this->arrReferrerSpammer;
    }
    
    public function generateProviderList()
    {
        if (false === $this->cachePath) 
        {
            return false;
        }
        
        foreach($this->referrerProvider as $source => $url)
        {
            $raw    = file_get_contents($this->cachePath .'/'. strtolower($source) . '.txt');
            $list   = explode("\n", $raw);
            $this->arrReferrerSpammer = array_merge($this->arrReferrerSpammer, $list);
        }
      
        if (count($this->arrReferrerSpammer))
        {
            return true;
        }
        return false;
    }
    
    public function cleanProviderList()
    {
        $this->arrReferrerSpammer = array_unique($this->arrReferrerSpammer); // doppelte Elemente löschen

        foreach ($this->arrReferrerSpammer as &$url)
        {
            $referrerHost = parse_url( $url, PHP_URL_HOST );
            if ($referrerHost === NULL)
            {
                $referrerHost = @parse_url( 'http://'.$url, PHP_URL_HOST );
            }
            $url = strtolower($referrerHost);
            $url = trim($url);
            $punicode = new Punycode();
            try 
            {
                $url = $punicode->encode($url);
            }
            catch (LabelOutOfBoundsException $e)
            {
                unset($url);
            }
            
        }
        sort($this->arrReferrerSpammer);
        $this->arrReferrerSpammer = array_filter($this->arrReferrerSpammer); // leere Elemente löschen
    }
    
    public function writeProviderList()
    {
        if (count($this->arrReferrerSpammer))
        {
            @unlink($this->cachePath .'/referrerblocked.txt');
            file_put_contents($this->cachePath .'/referrerblocked.txt', '|' . implode("|", $this->arrReferrerSpammer) . '|' . PHP_EOL);
            return true;
        }
        return false;
        
    }
}

