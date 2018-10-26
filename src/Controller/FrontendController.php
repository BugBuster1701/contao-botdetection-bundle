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

namespace BugBuster\RoutingappBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

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
        $strBuffer = '<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="utf-8">
  <title>BugBuster BotDetection Bundle - Manual Tests</title>
</head>
<body>
       <h1>BugBuster BotDetection Bundle - Manual Tests</h2>
       <p>Parameter: '.$token.'</p>
</body>
</html>';
        $objResponse = new Response($strBuffer);
        $objResponse->headers->set('Content-Type', 'text/html; charset=UTF-8');

        return $objResponse;
    }
}
