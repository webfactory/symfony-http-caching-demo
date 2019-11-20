<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DateTimeController extends AbstractController
{
    /**
     * @Route("/")
     * @Cache(maxage=5)
     */
    public function showTime(): Response
    {
        $response = $this->render('index.html.twig');

        return $response;
    }
}
