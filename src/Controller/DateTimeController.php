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
        return $this->render('index.html.twig');
    }
}
