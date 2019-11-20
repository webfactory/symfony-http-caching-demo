<?php

namespace App\Tests\BehatContext;

use Behat\MinkExtension\Context\RawMinkContext;

class HttpCachingContext extends RawMinkContext
{
    /**
     * @Given /^the expiration time should be (\d+) seconds$/
     */
    public function theExpirationTimeShouldAtLeastBeSeconds($seconds)
    {
        $this->assertSession()->responseHeaderMatches('Cache-Control', "_\bmax-age=$seconds\b_");
    }

    /**
     * @Given /^the response should be (public|private)$/
     */
    public function theResponseShouldBe($directive)
    {
        $this->assertSession()->responseHeaderMatches('Cache-Control', "_\b$directive\b_");
    }

    /**
     * @Given /^the HttpCache is enabled$/
     */
    public function theHttpCacheIsEnabled()
    {
        $this->getSession()->setCookie('SYMFONY_CACHE', 1);
    }

    /**
     * @Then /^the response is served from cache$/
     */
    public function theResponseIsServedFromCache()
    {
        $this->assertSession()->responseHeaderMatches('Age', '_\d+_');
    }

    /**
     * @Given /^the HttpCache trace contains \'([^\']*)\'$/
     */
    public function theHttpCacheTraceContains($arg1)
    {
        $this->assertSession()->responseHeaderContains('X-Symfony-Cache', $arg1);
    }

    /**
     * @Given /^revalidate the response$/
     */
    public function revalidateTheResponse()
    {
        $session = $this->getSession();
        $client = $session->getDriver()->getClient();
        $request = $client->getHistory()->current();
        $serverParameters = $request->getServer();

        if ($lastModified = $session->getResponseHeader('Last-Modified')) {
            $serverParameters['HTTP_IF_MODIFIED_SINCE'] = $lastModified;
        }

        if ($eTag = $session->getResponseHeader('Etag')) {
            $serverParameters['HTTP_IF_NONE_MATCH'] = $eTag;
        }

        $client->request($request->getMethod(), $request->getUri(), $request->getParameters(), $request->getFiles(), $serverParameters, $request->getContent());
    }
}
