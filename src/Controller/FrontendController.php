<?php

declare(strict_types=1);

/*
 * This file is part of a BugBuster Contao Bundle
 *
 * @copyright  Glen Langer 2018 <http://contao.ninja>
 * @author     Glen Langer (BugBuster)
 * @package    Contao BotDetection Bundle
 * @license    LGPL-3.0-or-later
 * @see        https://github.com/BugBuster1701/contao-botdetection-bundle
 */

namespace BugBuster\BotdetectionBundle\Controller;

use BugBuster\BotdetectionBundle\Functional\BotDetectionTests;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Handles front end routes.
 */
class FrontendController extends Controller
{
    /**
     * Renders the content.
     */
    public function manualtestsAction(string $token): Response
    {
        if (1701 !== $token) {
            throw new AccessDeniedException("You don't have access to this page!");
        }
        $objBuffer = new BotDetectionTests();
        $strBuffer = $objBuffer->run();

        $objResponse = new Response($strBuffer);
        $objResponse->headers->set('Content-Type', 'text/html; charset=UTF-8');

        return $objResponse;
    }
}
