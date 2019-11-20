<?php

namespace App\Tests\BehatContext;

use Behat\Behat\Tester\Exception\PendingException;
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
}
