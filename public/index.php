<?php

include __DIR__ . '/../vendor/autoload.php';
include __DIR__ . '/../app_bootstrap.php';
/** @var ALI\ALIAbc $ali */
$ali = include __DIR__ . '/../ali_bootstrap.php';

$ali->initSourceBuffering();

$ali->getBuffer()->start();
echo file_get_contents(__DIR__ . '/../content/test.html');
$ali->getBuffer()->end();