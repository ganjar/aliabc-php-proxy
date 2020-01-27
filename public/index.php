<?php

include __DIR__ . '/../vendor/autoload.php';
include __DIR__ . '/../app_bootstrap.php';
/** @var ALI\ALIAbc $ali */
$ali = include __DIR__ . '/../ali_bootstrap.php';
/** @var Proxy\Proxy $proxy */
$proxy = include __DIR__ . '/../proxy_bootstrap.php';

$ali->initSourceBuffering();

$ali->getBuffer()->start();

// Forward the request and get the response.
$response = $proxy->forward($request)->to(getenv('PROXY_TARGET'));

// Output response to the browser.
(new Zend\Diactoros\Response\SapiEmitter)->emit($response);

$ali->getBuffer()->end();