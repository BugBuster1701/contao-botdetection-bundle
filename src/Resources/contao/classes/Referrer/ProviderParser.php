<?php

/*
 * This file is part of a BugBuster Contao Bundle (Resources\contao)
 *
 * @copyright  Glen Langer 2024 <http://contao.ninja>
 * @author     Glen Langer (BugBuster)
 * @package    Contao BotDetection Bundle
 * @license    LGPL-3.0-or-later
 * @see        https://github.com/BugBuster1701/contao-botdetection-bundle
 */

namespace BugBuster\BotDetection\Referrer;

/**
 * Class ProviderParser
 *
 * @copyright  Glen Langer 2020 <http://contao.ninja>
 * @author     Glen Langer (BugBuster)
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

    private $referrerProvider;

    /**
     * @param array  $referrerProvider
     * @param string $cachePath
     */
    public function __construct($referrerProvider, $cachePath = false)
    {
        $this->referrerProvider    = $referrerProvider;
        $this->cachePath           = $cachePath;
        $this->arrReferrerSpammer  = array();
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

        if (\count($this->arrReferrerSpammer))
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
            $referrerHost = parse_url($url, PHP_URL_HOST);
            if ($referrerHost === NULL)
            {
                $referrerHost = @parse_url('http://'.$url, PHP_URL_HOST);
            }
            $url = strtolower($referrerHost);
            $url = trim($url);
            if (($url = idn_to_ascii($url)) === false)
            {
                unset($url);
            }
        }
        sort($this->arrReferrerSpammer);
        $this->arrReferrerSpammer = array_filter($this->arrReferrerSpammer); // leere Elemente löschen
    }

    public function writeProviderList()
    {
        if (\count($this->arrReferrerSpammer))
        {
            @unlink($this->cachePath .'/referrerblocked.txt');
            $ret = file_put_contents($this->cachePath .'/referrerblocked.txt', '|' . implode("|", $this->arrReferrerSpammer) . '|' . PHP_EOL);
            if (false !== $ret)
            {
                return true;
            }
        }

        return false;

    }

    /**
     * Test ob Update/Generierung von referrerblocked.txt notwendig
     * @return boolean true: ja, false nein
     */
    public function isUpdateProviderListNecessary()
    {
        $lastWeek = time() - (7 * 24 * 60 * 60);

        if (false === file_exists($this->cachePath .'/referrerblocked.txt') ||
             $lastWeek > filemtime($this->cachePath .'/referrerblocked.txt')
            )
        {
            return true; // update/anlegen notwendig
        }
        foreach($this->referrerProvider as $source => $url)
        {
            if (true === file_exists($this->cachePath .'/'. strtolower($source) . '.txt') &&
                $lastWeek > filemtime($this->cachePath .'/'. strtolower($source) . '.txt')
               )
            {
                return true; // update/anlegen notwendig, da eine Provider Datei neuer ist
            }
        }

        return false;
    }
}

