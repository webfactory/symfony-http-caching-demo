<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    /**
     * @Route("/time")
     */
    public function showTime(): Response
    {
        $response = $this->render('time.html.twig');
        $response->setMaxAge(10);
        $response->setPublic();

        return $response;
    }

    /**
     * @Route("/weekday")
     * @Cache(maxage=10, public=true)
     */
    public function showWeekday(Request $request): Response
    {
        $response = new Response();
        $response->setLastModified(new \DateTime('today midnight'));
        $response->setEtag('some-etag');

        if ($response->isNotModified($request)) {
            return $response;
        }

        $this->render('weekday.html.twig', [], $response);

        return $response;
    }
}
