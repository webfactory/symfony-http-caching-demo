# Symfony HTTP Caching Sandbox/Fiddle

This repo contains a basic Symfony Application that can be used to 
demonstrate how to do HTTP Caching with Symfony. It also contains the 
materials (very few slides) that I used to speak on the topic at the
[SymfonyCon Amsterdam 2019](https://amsterdam2019.symfony.com/). 

## About Me

My name is Matthias Pigulla. I lead the team at webfactory GmbH ([GitHub](https://github.com/webfactory), [Website](https://www.webfactory.de/), [Twitter](https://twitter.com/webfactory)) in Bonn, Germany,
where we do contract-based software development for various clients. Symfony is our
main and favorite platform that we have been using since the pre-release of 2.0 back in 2011. 

You can find my personal profiles on [GitHub](https://github.com/mpdude), [Twitter](https://twitter.com/mpdude_de) on [SymfonyConnect](https://connect.symfony.com/profile/mpdude).

## Using this

You should be able to clone this repo, run `composer install` and afterwards start the PHP
embedded server with `bin/console server:start 0.0.0.0:8000`. 

## Quick Reference

Very short, rough descriptions for the headers and `Cache-Control` values covered. See [RFC 7234](https://tools.ietf.org/html/rfc7234) and related 
specs for authoritative information.

`private`
: Response must not be stored in a shared cache. Private caches may store it, even if normally non-cacheable.

`no-cache`
: Response must not be used from cache without successful revalidation on the origin server.

`must-revalidate`
: Once the response has become stale, it must not be used from cache without successful revalidation on the origin server.

`no-store`
: Response must not be cached on permanent storage and caches should do their best to remove contents from memory etc. as soon as possible  

`max-age`
: Response lifetime (freshness) in seconds

`Age`
: Must be present when response has been served from cache. Conveys the time in seconds the response has already been in the cache.

`ETag`
: A token for revalidation, opaque (with no particular meaning) to the user-agent.

`Last-Modified`
: Time the resource was last changed on the origin server. Can be used (with 1 second precision) for revalidation and allows for "heuristic freshness" to be applied when not other expiration time was given and status codes that are either cacheable by default or caching has been explicitly allowed. 

`Vary`
: Lists additional HTTP request headers whose values must be used to key cached responses. 

`public`
: Explicitly defines a response as cacheable in shared caches. Even overrides special ruling if an `Authorization` header is present, so use cautiously.
 
## About the Talk

SymfonyCon finally gave me the opportunity to bring a bit more structure into what I 
have already been showing people before. This talk has been designed for the "Beginners"
track and so, apart from having some basic Symfony knowledge and maybe HTTP basics (what are
request methods, what are headers), you need not know anything about HTTP Caching in particular.

I have been trying to structure it in a way that allows me to progress from simple to more
complex, adding only one new concept at a time. Live demonstration and coding hopefully
will make it more comprehensible to the audience. Also, repetitions of previous concepts
shuold help to make things stick.

### Things covered:

* What is an HTTP Cache and what does it do?
* Cache layers and `public` vs. `private` caches
* How browsers behave as to caching and how you can use `curl` to debug
* Introduce `private`, `max-age` expiration-based caching; explain "fresh" and "stale"
* Introduce validation by adding `ETag` and `Last-Modified`, demoing conditional requests
* Optimize controller code by short-cutting the response on 304.
* Mention `must-revalidate` and heuristic expiration 
* Make expiration more explicit with either `no-cache` or `max-age`, this time combining expiration and validation
* Introduce the `HttpCache` implementation 
* Show `public` expiration caching and explain the cache trace as well as `Age` headers
* Show `public` caching with expiration and revalidation
* Warn on the implications of `public`, at least mention `Vary`.
* Introduce Egde Side Includes (ESI) and explain how this can be useful
* Show how ESI can be activated and used from Twig
* Show response body including `<esi:include>` markup
* Demo a showcase where `private` and `public` content with different expiration times can be mixed, cached and validated.

### Outtakes

Things I would have 😍 to include but was unable to due to being limited to 45 minutes:

* Work test-based by introducing `behat` and Mink/Goutte. Write scenarios as well as context steps for everything.
* Show how the Asset Component can be used to make assets cacheable, including `immutable` and cache busting/revving techniques
* Tell about `ab` and use it to show off performance improvements when using the `HttpCache`
* Show how to configure Apache Logging to record cache hit/miss rates. Use `grep`, `awk`, `sort` and `uniq` to find URLs/controllers that could benefit from caching and/or have bad hit rates. (https://symfony.com/blog/new-in-symfony-4-3-improved-httpcache-logging)
* Include a controller to demonstrate `Vary`, showing how requests e. g. with different cookie values can be separated.
* Show how to change Symfony environment, debug and caching by means of cookies (https://github.com/symfony/recipes/pull/679)

Bits and pieces for that, without further explanation:

#### Apache configuration

```
RewriteEngine On
RewriteCond %{DOCUMENT_ROOT}$1/$2 -f
RewriteRule ^(.*)/assets-version-\d+/(.*)$ $1/$2 [END,E=FARFUTURE_ASSET:1]
Header set Cache-Control "max-age=290304000, immutable, public" env=FARFUTURE_ASSET
Header set Last-Modified "Fri, 01 Jan 1988 00:00:00 GMT" env=FARFUTURE_ASSET
Header set Expires "Thu, 01 Jan 2037 00:00:00 GMT" env=FARFUTURE_ASSET
Header unset ETag env=FARFUTURE_ASSET

LogFormat "[%{%Y-%m-%dT%H:%M:%S}t.%{msec_frac}t%{%z}t] \"%r\" %>s %b %{UNIQUE_ID}e proc_time=%D cache_status=\"%{cache_status}n\"" cache_log
CustomLog     /var/www/symfony-http-caching/logs/cache_log cache_log
Header always note X-Cache cache_status
Header always unset X-Cache
``` 

#### Log analysis

```
# Can be used for cache hit/miss, response codes, request methods...
cat logs/cache_log | awk 'BEGIN { FPAT = "([^ ]+)|(\"[^\"]+\")" } { print $2 "\t" $3; }' | sort | uniq -c
``` 

#### Benchmarking

```
ab -c 5 -n 100 'http://127.0.0.1:8000/'
ab -c 5 -n 100 -C 'SYMFONY_CACHE=1' 'http://127.0.0.1:8000/'
```

#### `Vary` controller

```php
    /**
     * @Route("/vary")
     * @Cache(public=true, maxage=60)
     */
    public function vary(Request $request): Response
    {
        $response = new Response($request->cookies->getAlnum('demo') . "\n");
        $response->setVary('Cookie', false);

        return $response;
    }
```


