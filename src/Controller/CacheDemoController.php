<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CacheDemoController extends AbstractController
{
    /**
     * @Route("/request_headers")
     */
    public function requestHeaders(): Response
    {
        return $this->render('request_headers.html.twig');
    }
}
