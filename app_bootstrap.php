<?php

$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__, ['.env.example', '.env'], false);
$dotenv->load();
$dotenv->required('DEV_MODE')->isBoolean();

if ((bool)getenv('DEV_MODE')) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}