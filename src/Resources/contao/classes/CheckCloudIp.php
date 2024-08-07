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
class CheckCloudIp
{
    protected static $cloud_aws_json;
    protected static $cloud_azure_json;
    protected static $cloud_google_json;
    protected static $cloud_oracle_json;

    /**
     * Get the value of cloud_aws_json
     */ 
    public static function getCloud_aws_json()
    {
        return static::$cloud_aws_json;
    }

    /**
     * Set the value of cloud_aws_json
     */ 
    public static function setCloud_aws_json($cloud_aws_json)
    {
        static::$cloud_aws_json = $cloud_aws_json;
    }

    /**
     * Get the value of cloud_azure_json
     */ 
    public static function getCloud_azure_json()
    {
        return static::$cloud_azure_json;
    }

    /**
     * Set the value of cloud_azure_json
     */ 
    public static function setCloud_azure_json($cloud_azure_json)
    {
        static::$cloud_azure_json = $cloud_azure_json;
    }

    /**
     * Get the value of cloud_google_json
     */ 
    public static function getCloud_google_json()
    {
        return static::$cloud_google_json;
    }

    /**
     * Set the value of cloud_google_json
     */ 
    public static function setCloud_google_json($cloud_google_json)
    {
        static::$cloud_google_json = $cloud_google_json;
    }

    /**
     * Get the value of cloud_oracle_json
     */ 
    public static function getCloud_oracle_json()
    {
        return static::$cloud_oracle_json;
    }

    /**
     * Set the value of cloud_oracle_json
     */ 
    public static function setCloud_oracle_json($cloud_oracle_json)
    {
        static::$cloud_oracle_json = $cloud_oracle_json;
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
            if (\array_key_exists($key, $_SERVER) === true)
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
                if (static::checkCloudIPv4($UserIP) === true) { return true; }
                break;
            case "IPv6":
                if (static::checkCloudIPv6($UserIP) === true) { return true; }
                break;
            default:
                return false;
                break;
        }

        return false;

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
        if (substr_count($ip, ":") < 2) return false; // ::1 or 2001::0db8
        if (substr_count($ip, "::") > 1) return false; // one allowed

        $groups = explode(':', $ip);
        $num_groups = \count($groups);
        if (($num_groups > 8) || ($num_groups < 3)) return false;

        $empty_groups = 0;
        foreach ($groups as $group)
        {
            $group = trim($group);
            if (!empty($group) && !(is_numeric($group) && ($group == 0)))
            {
                if (!preg_match('#([a-fA-F0-9]{0,4})#', $group)) return false;
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
     * Cloud IP Check for IPv4
     *
     * @param string   User IP, optional for tests
     * @return boolean true when bot found over IP
     */
    protected static function checkCloudIPv4($UserIP = false)
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

        if ("IPv4" != static::getIpVersion($UserIP))
        {
            // no valid IPv4
            return false;
        }

        static::loadAwsJson();
        if (static::checkIp4InCloud($UserIP))
        {
            unset($GLOBALS['CLOUDDETECTION']);

            return true;
        }
        static::loadAzureBotJson();
        if (static::checkIp4InCloud($UserIP))
        {
            unset($GLOBALS['CLOUDDETECTION']);

            return true;
        }
        static::loadAzureJson();
        if (static::checkIp4InCloud($UserIP))
        {
            unset($GLOBALS['CLOUDDETECTION']);

            return true;
        }
        static::loadGoogleJson();
        if (static::checkIp4InCloud($UserIP))
        {
            unset($GLOBALS['CLOUDDETECTION']);

            return true;
        }
        static::loadOracleJson();
        if (static::checkIp4InCloud($UserIP))
        {
            unset($GLOBALS['CLOUDDETECTION']);

            return true;
        }
        unset($GLOBALS['CLOUDDETECTION']);

        return false;
    }

    /**
     * Cloud IP Check for IPv6
     *
     * @param string   User IP, optional for tests
     * @return boolean true when bot found over IP
     */
    protected static function checkCloudIPv6($UserIP = false)
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

        if ("IPv6" != static::getIpVersion($UserIP))
        {
            // no valid IPv6
            return false;
        }

        static::loadAwsJson();
        if (static::checkIp6InCloud($UserIP))
        {
            unset($GLOBALS['CLOUDDETECTION']);

            return true;
        }
        static::loadAzureBotJson();
        if (static::checkIp6InCloud($UserIP))
        {
            unset($GLOBALS['CLOUDDETECTION']);

            return true;
        }
        static::loadAzureJson();
        if (static::checkIp6InCloud($UserIP))
        {
            unset($GLOBALS['CLOUDDETECTION']);

            return true;
        }
        static::loadGoogleJson();
        if (static::checkIp6InCloud($UserIP))
        {
            unset($GLOBALS['CLOUDDETECTION']);

            return true;
        }
        static::loadOracleJson();
        if (static::checkIp6InCloud($UserIP))
        {
            unset($GLOBALS['CLOUDDETECTION']);

            return true;
        }
        unset($GLOBALS['CLOUDDETECTION']);

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

    protected static function loadAwsJson()
    {
        $cloudjson = file_get_contents(static::getCloud_aws_json());
        $cloud = json_decode($cloudjson);
        // Liste der IPs
        $i=0;
        while($cloud->{'prefixes'}[$i] ?? false)
        {
            if (!empty($cloud->{'prefixes'}[$i]->{'ip_prefix'}))
            {
                $GLOBALS['CLOUDDETECTION']['CLOUD_IP'][] = $cloud->{'prefixes'}[$i]->{'ip_prefix'};
            }
            $i++;
        }
        $i=0;
        while($cloud->{'ipv6_prefixes'}[$i] ?? false)
        {
            if (!empty($cloud->{'ipv6_prefixes'}[$i]->{'ipv6_prefix'}))
            {
                $GLOBALS['CLOUDDETECTION']['CLOUD_IPV6'][] = $cloud->{'ipv6_prefixes'}[$i]->{'ipv6_prefix'};
            }
            $i++;
        }

    }

    protected static function loadAzureBotJson()
    {
        $cloudjson = file_get_contents(static::getCloud_azure_json());
        $cloud = json_decode($cloudjson);
        // Liste der IPs AzureBotService
        $i=0;
        while($cloud[0]->{'addressPrefixes'}[$i] ?? false)
        {
            if (strpos($cloud[0]->{'addressPrefixes'}[$i], '.') !== false)
            {
                $GLOBALS['CLOUDDETECTION']['CLOUD_IP'][] = $cloud[0]->{'addressPrefixes'}[$i];
            }
            elseif (strpos($cloud[0]->{'addressPrefixes'}[$i], ':') !== false)
            {
                $GLOBALS['CLOUDDETECTION']['CLOUD_IPV6'][] = $cloud[0]->{'addressPrefixes'}[$i];
            }
            $i++;
        }
    }

    protected static function loadAzureJson()
    {
        $cloudjson = file_get_contents(static::getCloud_azure_json());
        $cloud = json_decode($cloudjson);
        // Liste der IPs AzureBotService
        $i=0;
        while($cloud[1]->{'addressPrefixes'}[$i] ?? false)
        {
            if (strpos($cloud[1]->{'addressPrefixes'}[$i], '.') !== false)
            {
                $GLOBALS['CLOUDDETECTION']['CLOUD_IP'][] = $cloud[1]->{'addressPrefixes'}[$i];
            }
            elseif (strpos($cloud[1]->{'addressPrefixes'}[$i], ':') !== false)
            {
                $GLOBALS['CLOUDDETECTION']['CLOUD_IPV6'][] = $cloud[1]->{'addressPrefixes'}[$i];
            }
            $i++;
        }
    }

    protected static function loadGoogleJson()
    {
        $cloudjson = file_get_contents(static::getCloud_google_json());
        $cloud = json_decode($cloudjson);
        // Liste der IPs
        $i=0;
        while($cloud->{'prefixes'}[$i] ?? false)
        {
            if (!empty($cloud->{'prefixes'}[$i]->{'ipv4Prefix'}))
            {
                $GLOBALS['CLOUDDETECTION']['CLOUD_IP'][] = $cloud->{'prefixes'}[$i]->{'ipv4Prefix'};
            }
            elseif (!empty($cloud->{'prefixes'}[$i]->{'ipv6Prefix'}))
            {
                $GLOBALS['CLOUDDETECTION']['CLOUD_IPV6'][] = $cloud->{'prefixes'}[$i]->{'ipv6Prefix'};
            }
            $i++;
        }        
    }

    protected static function loadOracleJson()
    {
        $cloudjson = file_get_contents(static::getCloud_oracle_json());
        $cloud = json_decode($cloudjson);
        $i = 0;
        while ($cloud->{'regions'}[$i] ?? false)
        {
            $region = $cloud->{'regions'}[$i];
            $j = 0;
            while ($region->{'cidrs'}[$j] ?? false)
            {
                if (!empty($region->{'cidrs'}[$j]->{'cidr'}))
                {
                    if (strpos($region->{'cidrs'}[$j]->{'cidr'}, '.') !== false)
                    {
                        $GLOBALS['CLOUDDETECTION']['CLOUD_IP'][] = $region->{'cidrs'}[$j]->{'cidr'};
                    }
                    elseif (strpos($region->{'cidrs'}[$j]->{'cidr'}, ':') !== false)
                    {
                        $GLOBALS['CLOUDDETECTION']['CLOUD_IPV6'][] = $region->{'cidrs'}[$j]->{'cidr'};
                    }
                }
                $j++;
            }
            $i++;
        }
    }

    protected static function checkIp4InCloud($UserIP)
    {
        if (isset($GLOBALS['CLOUDDETECTION']['CLOUD_IP']))
        {
            foreach ($GLOBALS['CLOUDDETECTION']['CLOUD_IP'] as $lineleft)
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

    protected static function checkIp6InCloud($UserIP)
    {
        if (isset($GLOBALS['CLOUDDETECTION']['CLOUD_IPV6']))
        {
            foreach ($GLOBALS['CLOUDDETECTION']['CLOUD_IPV6'] as $lineleft)
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
}
