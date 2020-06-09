<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\EventListener\AbstractSessionListener;
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
    public function timeAction(): Response
    {
        $response = $this->render('time.html.twig');
        $response->setMaxAge(30);
        $response->setPublic();
        $response->headers->set(AbstractSessionListener::NO_AUTO_CACHE_CONTROL_HEADER, 'true');

        return $response;
    }

    /**
     * @Route("/weekday")
     */
    public function weekdayAction(Request $request): Response
    {
        $response = new Response();
        $response->setLastModified(new \DateTime('today midnight'));
        $response->setEtag('some-etag');
        $response->setMaxAge(30);
        $response->setPublic();
        $response->headers->set(AbstractSessionListener::NO_AUTO_CACHE_CONTROL_HEADER, 'true');

        if ($response->isNotModified($request)) {
            return $response;
        }

        $this->render('weekday.html.twig', [], $response);

        return $response;
    }

    /**
     * @Route("/")
     */
    public function dashboardAction()
    {
        return $this->render('dashboard.html.twig');
    }
}
