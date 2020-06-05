<?php
require_once("../vendor/autoload.php");
use DcrPHP\Config\Config;

try {
    $clsConfig = new Config(__DIR__ . '\config');
    $clsConfig->set('my', array('email'=>'junqing124@126.com'));

    print_r($clsConfig->get());
    echo "\r\n";
    print_r($clsConfig->get('app.default_timezone'));
    echo "\r\n";
    print_r($clsConfig->get('my.email'));
    echo "\r\n";
    print_r($clsConfig->getByFile(__DIR__ . '\config\app.php','app.session_life_time'));
} catch (Exception $e) {
}
