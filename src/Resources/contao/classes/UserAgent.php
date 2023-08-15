<?php

/*
 * This file is part of a BugBuster Contao Bundle (Resources\contao)
 *
 * @copyright  Glen Langer 2023 <http://contao.ninja>
 * @author     Glen Langer (BugBuster)
 * @package    Contao BotDetection Bundle
 * @license    LGPL-3.0-or-later
 * @see        https://github.com/BugBuster1701/contao-botdetection-bundle
 */

namespace BugBuster\BotDetection;

class UserAgent
{
	/**
	 * Exclusions - Definition
	 * Parts of this class are based on the brilliant Crawler-Detect
	 * 
	 * @var array
	 */
	protected   $Exclusions = array( 
                                'cubot',
                                '; M bot',
                                '; CRONO',
                                '; B bot',
                                '; IDbot',
                                '; ID bot',
                                '; POWER BOT',
                                'ForwardRuby',
                                'Java;',
                                '[Pinterest/Android]',
                                '[Pinterest/iOS]',
                                'Lightning'
                            );

    /**
     * All possible HTTP headers that represent the user agent string.
     *
     * @var array
     */                            
    protected   $Headers = array(
                            // The default User-Agent string.
                            'HTTP_USER_AGENT',
                            // Header can occur on devices using Opera Mini.
                            'HTTP_X_OPERAMINI_PHONE_UA',
                            // Vodafone specific header: http://www.seoprinciple.com/mobile-web-community-still-angry-at-vodafone/24/
                            'HTTP_X_DEVICE_USER_AGENT',
                            'HTTP_X_ORIGINAL_USER_AGENT',
                            'HTTP_X_SKYFIRE_PHONE',
                            'HTTP_X_BOLT_PHONE_UA',
                            'HTTP_DEVICE_STOCK_UA',
                            'HTTP_X_UCBROWSER_DEVICE_UA',
                            // Sometimes, bots (especially Google) use a genuine user agent, but fill this header in with their email address
                            'HTTP_FROM',
                            'HTTP_X_SCANNER', // Seen in use by Netsparker
                        );
    /**
     * The user agent.
     *
     * @var string|null
     */
    protected $userAgent;

    /**
     * Headers that contain a user agent.
     *
     * @var array
     */
    protected $httpHeaders = array();

    /**
     * Class constructor.
     */
    public function __construct($userAgent = null)
    {
        $this->setHttpHeaders();
        $this->setUserAgent($userAgent);
    }

    /**
     * Set HTTP headers.
     *
     * @param array|null $httpHeaders
     */
    public function setHttpHeaders()
    {
        $httpHeaders = $_SERVER;

        // Clear existing headers.
        $this->httpHeaders = array();

        // Only save HTTP headers. In PHP land, that means
        // only _SERVER vars that start with HTTP_.
        foreach ($httpHeaders as $key => $value) {
            if (strpos($key, 'HTTP_') === 0) {
                $this->httpHeaders[$key] = $value;
            }
        }
    }

    /**
     * Set the user agent.
     *
     * @param string|null $userAgent
     */
    public function setUserAgent($userAgent = null)
    {
        if (\is_null($userAgent) or false === $userAgent) {
            foreach ($this->Headers as $altHeader) {
                if (isset($this->httpHeaders[$altHeader])) {
                    $userAgent .= $this->httpHeaders[$altHeader].' ';
                }
            }
        }

        return $this->userAgent = str_ireplace($this->Exclusions, '', $userAgent);
    }

    /**
     * Get the user agent
     *
     * @return string $userAgent
     */
    public function getUserAgent()
    {
        return $this->userAgent;
    }

}