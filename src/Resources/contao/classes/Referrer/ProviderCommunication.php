<?php
/**
 * BotDetection - ProviderCommunication
 *
 * @copyright  Glen Langer 2007..2020 <http://contao.ninja>
 * @author     Glen Langer (BugBuster)
 * @license    LGPL
 * @filesource
 * @see        https://github.com/BugBuster1701/contao-botdetection-bundle
 */

namespace BugBuster\BotDetection\Referrer;

use Contao\Folder;
use Contao\StringUtil;
use Contao\System;
use Symfony\Component\HttpClient\HttpClient;

/**
 * Class ProviderCommunication
 *
 * @copyright  Glen Langer 2020 <http://contao.ninja>
 * @author     Glen Langer (BugBuster)
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
     * @var bool
     */
    private $allowUrlOpen;

    /**
     * @var bool
     */
    private $isCurlEnabled;

    /**
     * TL_ROOT over Container
     * 
     * @var string
     */
    private $rootDir;

    /**
     * @param array  $referrerProvider
     * @param string $cachePath
     */
    public function __construct($referrerProvider, $cachePath = false, $rootDir = '') 
    {
        $this->referrerProvider = $referrerProvider;
        $this->cachePath        = $cachePath;
        $this->allowUrlOpen     = (bool) ini_get('allow_url_fopen');
        $this->isCurlEnabled    = \function_exists('curl_init');
        $this->rootDir          = $rootDir;

        $this->checkCachePath();

    }

    public function checkCachePath()
    {
        if (false === $this->cachePath)
        {
            //Cache Pfad anlegen sofern nötig
            $strCachePath   = StringUtil::stripRootDir(System::getContainer()->getParameter('kernel.cache_dir'));
            $objFolder      = new Folder($strCachePath . '/botdetection');
            unset($objFolder);
            $this->cachePath = $this->rootDir . '/' . $strCachePath . '/botdetection';
        }

        // Create the folder if it does not exist
        if (!is_dir($this->cachePath))
        {
            $strPath = '';
            $arrChunks = explode('/', $this->cachePath);

            // Create the folder
            foreach ($arrChunks as $strFolder)
            {
                $strPath .= ($strPath ? '/' : '') . $strFolder;
                @mkdir($strPath, 0775);
            }
        }
    }

    /**
     * Load Referrer Provider Files if
     * - provider files to old
     */
    public function loadProviderFiles()
    {
        //debug $this->logMessage('ProviderCommunication::loadProviderFile: START','botdetection_debug');
        $lastWeek = time() - (7 * 24 * 60 * 60);

        if (false === $this->allowUrlOpen && false === $this->isCurlEnabled)
        {
            $this->logMessage('ProviderCommunication::loadProviderFiles allowUrlOpen && CurlEnabled = false!', 'botdetection_debug');

            return false;
        }

        if (!is_dir($this->cachePath))
        {
            $this->logMessage('ProviderCommunication::loadProviderFiles '.$this->cachePath.' does not exist!', 'botdetection_debug');

            return false;
        }

        $httpClient   = HttpClient::create();
        foreach($this->referrerProvider as $source => $url) 
        {
            if (false === file_exists($this->cachePath .'/'. strtolower($source) . '.txt') || 
                $lastWeek > filemtime($this->cachePath .'/'. strtolower($source) . '.txt') 
               )
            {
                try {
                    $response = $httpClient->request('GET', $url, array('timeout' => 10));
                } catch (\Throwable $t) {
                    $responseBody = "Request Exception:\n".$t->getMessage()."\n";
                    $this->logMessage('ProviderCommunication::loadProviderFile Error: '.$responseBody, 'botdetection_debug');

                    return false;
                }
                //debug $this->logMessage('ProviderCommunication::loadProviderFile: '.$source,'botdetection_debug');
                $fileProvider = fopen($this->cachePath .'/'. strtolower($source) . '.txt', 'wb+');
                fwrite($fileProvider, $response->getContent());
                fclose($fileProvider);
                unset($response);
            }
        }
        //debug $this->logMessage('ProviderCommunication::loadProviderFile: END','botdetection_debug');
        return true;
    }

    /**
     * Wrapper for old log_message
     *
     * @param string $strMessage
     * @param string $strLogg
     */
    public function logMessage($strMessage, $strLog=null)
    {
        if ($strLog === null)
        {
            $strLog = 'prod-' . date('Y-m-d') . '.log';
        }
        else
        {
            $strLog = 'prod-' . date('Y-m-d') . '-' . $strLog . '.log';
        }

        $strLogsDir = null;

        if (($container = System::getContainer()) !== null)
        {
            $strLogsDir = $container->getParameter('kernel.logs_dir');
        }

        if (!$strLogsDir)
        {
            $strLogsDir = $this->rootDir . '/var/logs';
        }

        error_log(sprintf("[%s] %s\n", date('d-M-Y H:i:s'), $strMessage), 3, $strLogsDir . '/' . $strLog);
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

