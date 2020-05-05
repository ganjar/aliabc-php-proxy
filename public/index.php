<?php

use ALI\Translation\ALIAbc;

include __DIR__ . '/../vendor/autoload.php';
include __DIR__ . '/../app_bootstrap.php';
/** @var ALIAbc $ali */
$ali = include __DIR__ . '/../ali_bootstrap.php';
/** @var Proxy\Proxy $proxy */
$proxy = include __DIR__ . '/../proxy_bootstrap.php';

ob_start();

include __DIR__ . '/../language_switcher.php';

$langSwitcher = ob_get_clean();

ob_start();

// Forward the request and get the response.
$response = $proxy->forward($request)->to(getenv('PROXY_TARGET'));
(new Zend\Diactoros\Response\SapiEmitter)->emit($response);
$proxyResponse = ob_get_clean();

//process http pages
$contentType = $response->getHeader('Content-Type');
if (!empty($contentType[0]) && strpos($contentType[0], 'text/html') !== false) {
    //replace absolute URLs with proxy URL
    $proxyResponse = str_replace(rtrim(getenv('PROXY_TARGET'), '/') . '/', '//' . $_SERVER['HTTP_HOST'] . '/', $proxyResponse);

    //ALIABC translate //todo - fix
    $proxyResponse = $ali->translateBuffer($proxyResponse);
    $proxyResponse = preg_replace('#(<body\s?[^>]*>)#', '$1 ' . $langSwitcher, $proxyResponse);
}

echo $proxyResponse;
