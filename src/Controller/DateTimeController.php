<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DateTimeController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function showTime(): Response
    {
        $response = $this->render('index.html.twig');
        $response->setMaxAge(5);

        return $response;
    }
}
