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
}
