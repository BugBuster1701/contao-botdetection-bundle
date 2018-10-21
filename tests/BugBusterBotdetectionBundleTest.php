<?php

/*
 * This file is part of a BugBuster Contao Bundle
 *
 * @copyright  Glen Langer 2018 <http://contao.ninja>
 * @author     Glen Langer (BugBuster)
 * @package    Contao BotDetection Bundle
 * @license    LGPL-3.0-or-later
 * @see        https://github.com/BugBuster1701/contao-botdetection-bundle
 */

namespace BugBuster\BotdetectionBundle\Tests;

use BugBuster\BotdetectionBundle\BugBusterBotdetectionBundle;
use PHPUnit\Framework\TestCase;

class BugBusterBotdetectionBundleTest extends TestCase
{
    public function testCanBeInstantiated()
    {
        $bundle = new BugBusterBotdetectionBundle();

        $this->assertInstanceOf('BugBuster\BotdetectionBundle\BugBusterBotdetectionBundle', $bundle);
    }
}
