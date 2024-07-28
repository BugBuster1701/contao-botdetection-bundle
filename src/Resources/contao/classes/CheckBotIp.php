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

namespace BugBuster\BotDetection;

/**
 * Class CheckBotIp 
 */
class CheckBotIp
{

    protected static $bot_ipv4_list;
    protected static $bot_ipv6_list;
    protected static $bot_bing_json;
    protected static $bot_google_json;
    protected static $bot_gpt_json;

    public static function setBotIpv4List($bot_ipv4_list)
    {
        static::$bot_ipv4_list = $bot_ipv4_list;
    }

    public static function setBotIpv6List($bot_ipv6_list)
    {
        static::$bot_ipv6_list = $bot_ipv6_list;
    }

    /**
     * Set the value of bot_bing_json
     *
     * @return self
     */ 
    public static function setBot_bing_json($bot_bing_json)
    {
        static::$bot_bing_json = $bot_bing_json;
    }

    /**
     * Set the value of bot_google_json
     *
     * @return self
     */ 
    public static function setBot_google_json($bot_google_json)
    {
        static::$bot_google_json = $bot_google_json;
    }

    /**
     * Set the value of bot_gpt_json
     *
     * @return self
     */ 
    public static function setBot_gpt_json($bot_gpt_json)
    {
        static::$bot_gpt_json = $bot_gpt_json;
    }

    public static function getBotIpv4List()
    {
        return static::$bot_ipv4_list;
    }

    public static function getBotIpv6List()
    {
        return static::$bot_ipv6_list;
    }

    /**
     * Get the value of bot_bing_json
     */ 
    public static function getBot_bing_json()
    {
        return static::$bot_bing_json;
    }

    /**
     * Get the value of bot_google_json
     */ 
    public static function getBot_google_json()
    {
        return static::$bot_google_json;
    }

    /**
     * Get the value of bot_gpt_json
     */ 
    public static function getBot_gpt_json()
    {
        return static::$bot_gpt_json;
    }

    public static function getUserIP()
    {
        // 1. HTTP_CF_CONNECTING_IP CloudFlare? 
        // 2. HTTP_CLIENT_IP?
        // 3. HTTP_X_FORWARDED_FOR?
        // 4. HTTP_X_FORWARDED?
        // 5. HTTP_X_CLUSTER_CLIENT_IP?
        // 6. HTTP_FORWARDED_FOR?
        // 7. HTTP_FORWARDED?
        // 8. REMOTE_ADDR?
        // 9. False!

        $ipkeys = array(
            'HTTP_CF_CONNECTING_IP',
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_X_CLUSTER_CLIENT_IP', 
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'REMOTE_ADDR'
            );

        foreach ($ipkeys as $key)
        {
            if (\array_key_exists($key, $_SERVER))
            {
                foreach (explode(',', $_SERVER[$key]) as $ip)
                {
                    $ip = trim($ip);

                    if (filter_var($ip, FILTER_VALIDATE_IP) !== false)
                    {
                        return $ip;
                    }
                }
            }
        }

        return false;
    }

    /**
     * Spider Bot IP Check
     * Detect the IP version and calls the method checkBotIPv4 respectively checkBotIPv6.
     *
     * @param string   User IP, optional for tests
     * @return boolean true when bot found over IP
     */
    public static function checkIP($UserIP = false)
    {
        // Check if IP present
        if ($UserIP === false)
        {
            $UserIP = static::getUserIP();

            if ($UserIP === false)
            {
                return false; // No IP, no search.
            }
        }

        // IPv4 or IPv6 ?
        switch (static::getIpVersion($UserIP))
        {
        	case "IPv4":
        	    if (static::checkBotIPv4($UserIP, static::getBotIpv4List()) === true) { return true; }
        	    break;
        	case "IPv6":
        	    if (static::checkBotIPv6($UserIP, static::getBotIpv6List()) === true) { return true; }
        	    break;
        	default:
        	    return false;
        	    break;
        }

        return false;
    }

    /**
     * Spider Bot IP Check for IPv4
     *
     * @param string   User IP, optional for tests
     * @return boolean true when bot found over IP
     * @param  string  $Bot_IPv4_List Bot IPv6 List, absolute Path+Filename including TL_ROOT
     */
    protected static function checkBotIPv4($UserIP = false, $Bot_IPv4_List = false)
    {
        if (false === $Bot_IPv4_List) 
        {
        	return false;
        }
        // Check if IP present
        if ($UserIP === false)
        {
            $UserIP = static::getUserIP();

            if ($UserIP === false)
            {
                return false; // No IP, no search.
            }
        }

        if ("IPv4" != static::getIpVersion($UserIP))
        {
            // no valid IPv4
            return false;
        }

        // search user IP in bot-ip-list
        if (file_exists($Bot_IPv4_List))
        {
            $source = file($Bot_IPv4_List);
            foreach ($source as $line)
            {
                if ($line[0] === '#')
                {
                    continue;
                }
                $lineleft = explode("#", $line); // Abtrennen Netzwerk/Mask von Bemerkung
                $network = explode("/", trim($lineleft[0]));
                if (!isset($network[1]))
                {
                    $network[1] = 32;
                }
                if (static::checkIp4InNetwork($UserIP, $network[0], $network[1]))
                {
                    return true; // IP found
                }
            }
        }

        static::loadBingJson();
        static::loadGoogleJson();
        static::loadGptJson();

        // search for user bot IP-filter definitions in localconfig.php
        if (isset($GLOBALS['BOTDETECTION']['BOT_IP']))
        {
            foreach ($GLOBALS['BOTDETECTION']['BOT_IP'] as $lineleft)
            {
                $network = explode("/", trim($lineleft));
                if (!isset($network[1]))
                {
                    $network[1] = 32;
                }
                if (static::checkIp4InNetwork($UserIP, $network[0], $network[1]))
                {
                    return true; // IP found
                }
            }
        }

        return false;
    }

    /**
     * Spider Bot IP Check for IPv6
     *
     * @param string   User IP, optional for tests
     * @param  string  $Bot_IPv6_List Bot IPv6 List, absolute Path+Filename including TL_ROOT
     * @return boolean true when bot found over IP
     */
    protected static function checkBotIPv6($UserIP = false, $Bot_IPv6_List = false)
    {
        if (false === $Bot_IPv6_List)
        {
            return false;
        }
        // Check if IP present
        if ($UserIP === false)
        {
            $UserIP = static::getUserIP();

            if ($UserIP === false)
            {
                return false; // No IP, no search.
            }
        }

        if ("IPv6" != static::getIpVersion($UserIP))
        {
            // no valid IPv6
            return false;
        }

        // search user IP in bot-ip-list-ipv6
        if (file_exists($Bot_IPv6_List))
        {
            $source = file($Bot_IPv6_List);
            foreach ($source as $line)
            {
                if ($line[0] === '#') 
                {
                	continue;
                }
                $lineleft = explode("#", $line); // Abtrennen IP von Bemerkung
                $network = explode("/", trim($lineleft[0]));

                if (!isset($network[1]))
                {
                    $network[1] = 128;
                }
                if (static::checkIp6InNetwork($UserIP, $network[0], $network[1]))
                {
                    return true; // IP found
                }
            }
        }

        static::loadGoogleJson(); // nur der von denen hat IPv6 Adressen

        // search for user bot IP-filter definitions in localconfig.php
        if (isset($GLOBALS['BOTDETECTION']['BOT_IPV6']))
        {
            foreach ($GLOBALS['BOTDETECTION']['BOT_IPV6'] as $lineleft)
            {
                $network = explode("/", trim($lineleft));
                if (!isset($network[1]))
                {
                    $network[1] = 128;
                }
                if (static::checkIp6InNetwork($UserIP, $network[0], $network[1]))
                {
                    return true; // IP found
                }
            }
        }

        return false;
    }

    /**
     * Helperfunction, Replace '::' with appropriate number of ':0'
     *
     * @param  string $Ip IP Address
     * @return string IP Address expanded
     */
    protected static function expandNotationIpv6($Ip)
    {
        if (strpos($Ip, '::') !== false)
        {
            $Ip = str_replace('::', str_repeat(':0', 8 - substr_count($Ip, ':')).':', $Ip);
        }
        if (strpos($Ip, ':') === 0)
        {
            $Ip = '0'.$Ip;
        }
        //PHP7.4 fix for mapped IPv4 Addresses, #40
        if (strpos($Ip, '.') !== false)
        {
            $Ip = str_replace('.', '', $Ip);
        }

        return $Ip;
    }

    /**
     * Helperfunction, Convert IPv6 address to an integer
     *
     * Optionally split in to two parts.
     *
     * @see http://stackoverflow.com/questions/420680/
     * @param  string $Ip            IP Address
     * @param  int    $DatabaseParts 1 = one part, 2 = two parts (array)
     * @return mixed  string      / array
     */
    protected static function getIpv6ToLong($Ip, $DatabaseParts= 1)
    {
        $Ip = static::expandNotationIpv6($Ip);
        $Parts = explode(':', $Ip);
        $Ip = array('', '');
        for ($i = 0; $i < 4; $i++)
        {
            $Ip[0] .= str_pad(base_convert($Parts[$i], 16, 2), 16, 0, STR_PAD_LEFT);
        }
        for ($i = 4; $i < 8; $i++)
        {
            $Ip[1] .= str_pad(base_convert($Parts[$i], 16, 2), 16, 0, STR_PAD_LEFT);
        }

        if ($DatabaseParts == 2)
        {
            return array(base_convert($Ip[0], 2, 10), base_convert($Ip[1], 2, 10));
        }
        else
        {
            return base_convert($Ip[0], 2, 10) + base_convert($Ip[1], 2, 10);
        }
    }

    /**
     * Helperfunction, if IPv6 in NET_ADDR/PREFIX
     *
     * @param  string  $UserIP
     * @param  string  $net_addr
     * @param  integer $net_mask
     * @return boolean
     */
    protected static function checkIp6InNetwork($UserIP, $net_addr=0, $net_mask=0)
    {
        if ($net_mask <= 0)
        {
            return false;
        }
        // UserIP to bin
        $UserIP = static::expandNotationIpv6($UserIP);
        $Parts  = explode(':', $UserIP);
        $Ip = array('', '');
        for ($i = 0; $i < 8; $i++)
        {
            $Ip[0] .= str_pad(base_convert($Parts[$i], 16, 2), 16, 0, STR_PAD_LEFT);
        }
        // NetAddr to bin
        $net_addr = static::expandNotationIpv6($net_addr);
        $Parts    = explode(':', $net_addr);
        for ($i = 0; $i < 8; $i++)
        {
            $Ip[1] .= str_pad(base_convert($Parts[$i], 16, 2), 16, 0, STR_PAD_LEFT);
        }

        // compare the IPs
        return substr_compare($Ip[0], $Ip[1], 0, $net_mask) === 0;
    }

    /**
     * Helperfunction, if IPv4 in NET_ADDR/NET_MASK
     *
     * @param  string  $ip       IPv4 Address
     * @param  string  $net_addr Network, optional
     * @param  int     $net_mask Mask, optional
     * @return boolean
     */
    protected static function checkIp4InNetwork($ip, $net_addr=0, $net_mask=0)
    {
        if ($net_mask <= 0)
        {
            return false;
        }
        if (ip2long($net_addr)===false)
        {
            return false; //no IP
        }
        //php.net/ip2long : jwadhams1 at yahoo dot com
        $ip_binary_string  = sprintf("%032b", ip2long($ip));
        $net_binary_string = sprintf("%032b", ip2long($net_addr));

        return substr_compare($ip_binary_string, $net_binary_string, 0, $net_mask) === 0;
    }

    /**
     * Helperfunction, IP =  IPv4 or IPv6 ?
     *
     * @param  string $ip IP Address (IPv4 or IPv6)
     * @return mixed  false: no valid IPv4 and no valid IPv6
     *                "IPv4" : IPv4 Address
     *                "IPv6" : IPv6 Address
     */
    protected static function getIpVersion($ip)
    {
        // Test for IPv4
        if (ip2long($ip) !== false)
        {
            return "IPv4";
        }

        // Test for IPv6
        if (substr_count($ip, ":") < 2) {
            // ::1 or 2001::0db8
            return false; 
        }
        if (substr_count($ip, "::") > 1) {
            // one allowed
            return false; 
        }

        $groups = explode(':', $ip);
        $num_groups = \count($groups);
        if (($num_groups > 8) || ($num_groups < 3)) {
            return false;
        }

        $empty_groups = 0;
        foreach ($groups as $group)
        {
            $group = trim($group);
            if ($group !== '' && $group !== '0' && !(is_numeric($group) && ($group == 0)))
            {
                if (!preg_match('#([a-fA-F0-9]{0,4})#', $group)) {
                    return false;
                }
            }
            else
            {
                ++$empty_groups;
            }
        }
		if ($empty_groups < $num_groups)
		{
			return "IPv6";
		}

		return false; // no (valid) IP Address
	}

	/**
	 * Check if an IP address is from private or reserved ranges.
	 *
	 * @param  string  $UserIP
	 * @return boolean true = private/reserved
	 */
	protected static function checkPrivateIP($UserIP=false)
	{
	    return !filter_var($UserIP, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE);
	}

    protected static function loadBingJson()
    {
        $botjson = file_get_contents(static::getBot_bing_json());
        $bot = json_decode($botjson);
        // Liste der IPs
        $i=0;
        while($bot->{'prefixes'}[$i] ?? false)
        {
            if (!empty($bot->{'prefixes'}[$i]->{'ipv4Prefix'}))
            {
                $GLOBALS['BOTDETECTION']['BOT_IP'][] = $bot->{'prefixes'}[$i]->{'ipv4Prefix'};
            }
            if (!empty($bot->{'prefixes'}[$i]->{'ipv6Prefix'}))
            {
                $GLOBALS['BOTDETECTION']['BOT_IPV6'][] = $bot->{'prefixes'}[$i]->{'ipv6Prefix'};
            }
            $i++;
        }
    }

    protected static function loadGoogleJson()
    {
        $botjson = file_get_contents(static::getBot_google_json());
        $bot = json_decode($botjson);
        // Liste der IPs
        $i=0;
        while($bot->{'prefixes'}[$i] ?? false)
        {
            if (!empty($bot->{'prefixes'}[$i]->{'ipv4Prefix'}))
            {
                $GLOBALS['BOTDETECTION']['BOT_IP'][] = $bot->{'prefixes'}[$i]->{'ipv4Prefix'};
            }
            if (!empty($bot->{'prefixes'}[$i]->{'ipv6Prefix'}))
            {
                $GLOBALS['BOTDETECTION']['BOT_IPV6'][] = $bot->{'prefixes'}[$i]->{'ipv6Prefix'};
            }
            $i++;
        }
    }

    protected static function loadGptJson()
    {
        $botjson = file_get_contents(static::getBot_gpt_json());
        $bot = json_decode($botjson);
        // Liste der IPs
        $i=0;
        while($bot->{'prefixes'}[$i] ?? false)
        {
            if (!empty($bot->{'prefixes'}[$i]->{'ipv4Prefix'}))
            {
                $GLOBALS['BOTDETECTION']['BOT_IP'][] = $bot->{'prefixes'}[$i]->{'ipv4Prefix'};
            }
            if (!empty($bot->{'prefixes'}[$i]->{'ipv6Prefix'}))
            {
                $GLOBALS['BOTDETECTION']['BOT_IPV6'][] = $bot->{'prefixes'}[$i]->{'ipv6Prefix'};
            }
            $i++;
        }
    }
}
