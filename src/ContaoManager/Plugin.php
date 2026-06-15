<?php

declare(strict_types=1);

/*
 * This file is part of a BugBuster Contao Bundle
 *
 * @copyright  Glen Langer 2024 <http://contao.ninja>
 * @author     Glen Langer (BugBuster)
 * @package    Contao BotDetection Bundle
 * @license    LGPL-3.0-or-later
 * @see        https://github.com/BugBuster1701/contao-botdetection-bundle
 */

namespace BugBuster\BotdetectionBundle\ContaoManager;

use BugBuster\BotdetectionBundle\BugBusterBotdetectionBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Contao\ManagerPlugin\Routing\RoutingPluginInterface;
use Symfony\Component\Config\Loader\LoaderResolverInterface;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Plugin for the Contao Manager.
 */
class Plugin implements BundlePluginInterface, RoutingPluginInterface
{
    /**
     * Gets a list of autoload configurations for this bundle.
     *
     * @return array<ConfigInterface>
     */
    public function getBundles(ParserInterface $parser): array
    {
        return [
            BundleConfig::create(BugBusterBotdetectionBundle::class)
                ->setLoadAfter(['Contao\CoreBundle\ContaoCoreBundle'])
                ->setReplace(['botdetection']),
        ];
    }

    /**
     * Returns a collection of routes for this bundle.
     *
     * @param LoaderResolverInterface $resolver
     * @param KernelInterface $kernel
     * @return RouteCollection|null
     */
    public function getRouteCollection(LoaderResolverInterface $resolver, KernelInterface $kernel)
    {
        return $resolver
        ->resolve(__DIR__.'/../Resources/config/routing.yml')
        ->load(__DIR__.'/../Resources/config/routing.yml')
        ;
    }
}
