<?php

include __DIR__ . '/../vendor/autoload.php';
include __DIR__ . '/../app_bootstrap.php';
/** @var ALI\ALIAbc $ali */
$ali = include __DIR__ . '/../ali_bootstrap.php';
/** @var Proxy\Proxy $proxy */
$proxy = include __DIR__ . '/../proxy_bootstrap.php';

ob_start();

include __DIR__ . '/../language_switcher.php';

$langSwitcher = ob_get_clean();

ob_start();

// Forward the request and get the response.
$response = $proxy->forward($request)->to(getenv('PROXY_TARGET'));

// Output response to the browser.
(new Zend\Diactoros\Response\SapiEmitter)->emit($response);
$proxyResponse = ob_get_clean();

//translate only http pages
$contentType = $response->getHeader('Content-Type');
if (!empty($contentType[0]) && strpos($contentType[0], 'text/html') !== false) {
    $proxyResponse = str_replace(rtrim(getenv('PROXY_TARGET'), '/') . '/', '//' . $_SERVER['HTTP_HOST'] . '/',
        $proxyResponse);
    $proxyResponse = preg_replace('#(<body\s?[^>]*>)#', '$1 ' . $langSwitcher, $proxyResponse);

    echo $ali->getBufferTranslate()->translateBuffer($proxyResponse);
} else {
    echo $proxyResponse;
}