<?php

/**
 * @copyright  Glen Langer 2008..2017 <http://contao.ninja>
 * @author     Glen Langer (BugBuster)
 * @package    Botdetection
 * @license    LGPL-3.0+
 * @see	       https://github.com/BugBuster1701/contao-botdetection-bundle
 *
 */

namespace BugBuster\BotdetectionBundle\ContaoManager;

use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;

/**
 * Plugin for the Contao Manager.
 *
 * @author Glen Langer (BugBuster)
 */
class Plugin implements BundlePluginInterface
{
    /**
     * {@inheritdoc}
     */
    public function getBundles(ParserInterface $parser)
    {
        return [
            BundleConfig::create('BugBuster\BotdetectionBundle\BugBusterBotdetectionBundle')
                ->setLoadAfter(['Contao\CoreBundle\ContaoCoreBundle'])
                ->setReplace(['botdetection']),
        ];
    }
}
