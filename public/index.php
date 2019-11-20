<?php

use App\Kernel;
use Symfony\Bundle\FrameworkBundle\HttpCache\HttpCache;
use Symfony\Component\Debug\Debug;
use Symfony\Component\HttpFoundation\Request;

// See https://github.com/symfony/recipes/pull/679/
// When using the built-in server, start it like "SYMFONY_WEBAPP_KERNEL_PARAMETERS_ALLOW_OVERRIDE=1 bin/console server:start 0.0.0.0:8000"
unset($_COOKIE['XDEBUG_SESSION']);
if ($_SERVER['SYMFONY_WEBAPP_KERNEL_PARAMETERS_ALLOW_OVERRIDE'] ?? $_ENV['SYMFONY_WEBAPP_KERNEL_PARAMETERS_ALLOW_OVERRIDE'] ?? false) {
    if (isset($_COOKIE['SYMFONY_ENV'])) {
        $_ENV['APP_ENV'] = $_SERVER['APP_ENV'] = $_COOKIE['SYMFONY_ENV'];
        unset($_COOKIE['SYMFONY_ENV']);
    }
    if (isset($_COOKIE['SYMFONY_NODEBUG'])) {
        $_ENV['APP_DEBUG'] = $_SERVER['APP_DEBUG'] = false;
        unset($_COOKIE['SYMFONY_NODEBUG']);
    }
    if (isset($_COOKIE['SYMFONY_CACHE'])) {
        $_ENV['SYMFONY_WEBAPP_CACHE_ENABLED'] = $_SERVER['SYMFONY_WEBAPP_CACHE_ENABLED'] = true;
        unset($_COOKIE['SYMFONY_CACHE']);
    }
}

require dirname(__DIR__).'/config/bootstrap.php';

if ($_SERVER['APP_DEBUG']) {
    umask(0000);

    Debug::enable();
}

if ($trustedProxies = $_SERVER['TRUSTED_PROXIES'] ?? $_ENV['TRUSTED_PROXIES'] ?? false) {
    Request::setTrustedProxies(explode(',', $trustedProxies), Request::HEADER_X_FORWARDED_ALL ^ Request::HEADER_X_FORWARDED_HOST);
}

if ($trustedHosts = $_SERVER['TRUSTED_HOSTS'] ?? $_ENV['TRUSTED_HOSTS'] ?? false) {
    Request::setTrustedHosts([$trustedHosts]);
}

$kernel = new Kernel($_SERVER['APP_ENV'], (bool) $_SERVER['APP_DEBUG']);
$request = Request::createFromGlobals();

if ($_SERVER['SYMFONY_WEBAPP_CACHE_ENABLED'] ?? $_ENV['SYMFONY_WEBAPP_CACHE_ENABLED'] ?? false) {
    class AppCache extends HttpCache
    {
        protected function getOptions()
        {
            if (!$_SERVER['APP_DEBUG']) {
                return ['trace_level' => 'short', 'trace_header' => 'X-Cache'];
            }

            return [];
        }
    }
    $kernel = new AppCache($kernel);
}

$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
