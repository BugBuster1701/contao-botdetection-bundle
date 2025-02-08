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
 * Run in a custom namespace
 */

namespace BugBuster\BotDetection;

use BugBuster\BotDetection\Referrer\ProviderCommunication;
use BugBuster\BotDetection\Referrer\ProviderParser;
use Contao\System;

/**
 * Class ModuleBotDetection
 *
 * @copyright  Glen Langer 2007..2020 <http://contao.ninja>
 * @author     Glen Langer (BugBuster)
 */
class ModuleBotDetection extends System
{

    /**
     * Current version of the class.
     */
    const BOTDETECTION_VERSION  = '1.14.2';

    const BOT_REFERRER_LIST     = "/vendor/bugbuster/contao-botdetection-bundle/src/Resources/contao/config/bot-referrer-list.php";
    const BOT_REFERRER_PROVIDER = "/vendor/bugbuster/contao-botdetection-bundle/src/Resources/contao/config/referrer-provider.php";

    const BOT_IP4_LIST          = "/vendor/bugbuster/contao-botdetection-bundle/src/Resources/contao/config/bot-ip-list-ipv4.txt";
    const BOT_IP6_LIST          = "/vendor/bugbuster/contao-botdetection-bundle/src/Resources/contao/config/bot-ip-list-ipv6.txt";

    const BOT_BING_JSON         = "/vendor/bugbuster/contao-botdetection-bundle/src/Resources/contao/config/bingbot.json";
    const BOT_GOOGLE_JSON       = "/vendor/bugbuster/contao-botdetection-bundle/src/Resources/contao/config/googlebot.json";
    const BOT_GPT_JSON          = "/vendor/bugbuster/contao-botdetection-bundle/src/Resources/contao/config/gptbot.json";

    const CLOUD_AWS_JSON        = "/vendor/bugbuster/contao-botdetection-bundle/src/Resources/contao/config/cloud_aws.json";
    const CLOUD_AZURE_JSON      = "/vendor/bugbuster/contao-botdetection-bundle/src/Resources/contao/config/cloud_azure.json";
    const CLOUD_GOOGLE_JSON     = "/vendor/bugbuster/contao-botdetection-bundle/src/Resources/contao/config/cloud_google.json";
    const CLOUD_ORACLE_JSON     = "/vendor/bugbuster/contao-botdetection-bundle/src/Resources/contao/config/cloud_oracle.json";
    const CLOUD_HETZNER_JSON    = "/vendor/bugbuster/contao-botdetection-bundle/src/Resources/contao/config/hetzner-cloud.json";

    /**
     * TL_ROOT over Container
     *
     * @var string
     */
    protected $rootDir;

    /**
     * Initialize object
     */
    public function __construct($rootDir = null)
    {
        parent::__construct();

        $this->rootDir = null === $rootDir ? System::getContainer()->getParameter('kernel.project_dir') : $rootDir;

        $this->prefillCache();
        $this->deleteOldCache();

    }

    /**
     * Returns the version number
     *
     * @return string
     */
    public function getVersion(): string
    {
        return self::BOTDETECTION_VERSION;
    }

    /**
     * Spider Bot Agent/Advanced/Referrer/IP Check
     *
     * @param string   UserAgent, optional for tests
     * @return boolean true when bot found
     */
    public function checkBotAllTests($UserAgent = false): bool
    {
        $objUserAgent = new UserAgent($UserAgent);
        $UserAgent = false === $UserAgent ? $objUserAgent->getUserAgent() : $objUserAgent->setUserAgent($UserAgent);

        if (CheckBotAgentSimple::checkAgent($UserAgent) === true) //(BotsRough, BotsFine)
        {
            return true;
        }

        if ((bool) CheckBotReferrer::checkReferrer(
            false,
            $this->rootDir . self::BOT_REFERRER_LIST,
            $this->rootDir . self::BOT_REFERRER_PROVIDER
        ) 
           )
        {
            return true;
        }

        if (false === $this->checkGetPostRequest()) // #153
        {
        	return true;
        }

        // ugly hack for my unit test
        if (CheckBotIp::getBotIpv4List() === NULL) {
            CheckBotIp::setBotIpv4List($this->rootDir . self::BOT_IP4_LIST);
        }
        if (CheckBotIp::getBotIpv6List() === NULL) {
            CheckBotIp::setBotIpv6List($this->rootDir . self::BOT_IP6_LIST);
        }
        if (CheckBotIp::getBot_bing_json() === NULL) {
            CheckBotIp::setBot_bing_json($this->rootDir . self::BOT_BING_JSON);
        }
        if (CheckBotIp::getBot_google_json() === NULL) {
            CheckBotIp::setBot_google_json($this->rootDir . self::BOT_GOOGLE_JSON);
        }
        if (CheckBotIp::getBot_gpt_json() === NULL) {
            CheckBotIp::setBot_gpt_json($this->rootDir . self::BOT_GPT_JSON);
        }

        if (true === CheckBotIp::checkIP())
        {
            return true;
        }

        if (CheckCloudIp::getCloud_aws_json() === NULL) {
            CheckCloudIp::setCloud_aws_json($this->rootDir . self::CLOUD_AWS_JSON);
        }
        if (CheckCloudIp::getCloud_azure_json() === NULL) {
            CheckCloudIp::setCloud_azure_json($this->rootDir . self::CLOUD_AZURE_JSON);
        }
        if (CheckCloudIp::getCloud_google_json() === NULL) {
            CheckCloudIp::setCloud_google_json($this->rootDir . self::CLOUD_GOOGLE_JSON);
        }
        if (CheckCloudIp::getCloud_oracle_json() === NULL) {
            CheckCloudIp::setCloud_oracle_json($this->rootDir . self::CLOUD_ORACLE_JSON);
        }
        if (CheckCloudIp::getCloud_hetzner_json() === NULL) {
            CheckCloudIp::setCloud_hetzner_json($this->rootDir . self::CLOUD_HETZNER_JSON);
        }

        if (true === CheckCloudIp::checkIP())
        {
            return true;
        }

        //CheckBotAgentExtended (Browscap + eigene Liste)
        return CheckBotAgentExtended::checkAgent($UserAgent);
    }

    /**
     * Check if Request a GET/POST Request
     * 
     * @return boolean true when GET/POST
     */
    public function checkGetPostRequest(): bool
    {
        $RequestMethod = \Contao\Environment::get('requestMethod');

        return $RequestMethod == 'GET' || $RequestMethod == 'POST';
    }

    private function prefillCache()
    {
        if (!is_dir($this->rootDir . '/vendor/bugbuster/contao-botdetection-bundle/src/Resources/contao/config'))
        {
            return; // call from IDE for tests
        }
        $referrerProvider = array();
        include_once($this->rootDir . '/vendor/bugbuster/contao-botdetection-bundle/src/Resources/contao/config/referrer-provider.php');
        $proCom = new ProviderCommunication($referrerProvider, false, $this->rootDir);
        $cachePath = false;

        if (true === $proCom->loadProviderFiles())
        {
            $cachePath = $proCom->getCachePath();
        }

        if (false !== $cachePath)
        {
            $proPar = new ProviderParser($referrerProvider, $cachePath);

            if (true === $proPar->isUpdateProviderListNecessary() &&
                true === $proPar->generateProviderList()
                )
            {
                $proPar->cleanProviderList();
                $proPar->writeProviderList();
            }
        }
    }

    private function deleteOldCache()
    {
        if (!is_dir($this->rootDir . '/vendor/bugbuster/contao-botdetection-bundle/src/Resources/contao/config'))
        {
            return; // call from IDE for tests
        }
        //Only once a month.
        //runonce replacement, until I know it better. (The package has modified files...., abort...)
        $day = (int) date('j');
        if (1 == $day)
        {
            $olddirs = array('largebrowscap_v6006_1.0.4', 
                             'largebrowscap_v6008_1.0.4', 
                             'largebrowscap_v6015_1.0.4', 
                             'largebrowscap_v6021_1.0.5', 
                             'largebrowscap_v6026_1.0.5', 
                             'largebrowscap_v6030_1.0.5',
                             'largebrowscap_v6036_1.0.5',
                             'largebrowscap_v6040_1.0.5',
                             'largebrowscap_v6040_1.1.0'
                        );
            foreach ($olddirs as $olddir)
            {
                if (is_dir($this->rootDir . '/vendor/bugbuster/contao-botdetection-bundle/src/Resources/contao/cache/'.$olddir))
                {
                    $folder = new \Contao\Folder('vendor/bugbuster/contao-botdetection-bundle/src/Resources/contao/cache/'.$olddir);
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
    }
}
