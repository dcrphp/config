<?php
require_once("../vendor/autoload.php");
use DcrPHP\Config\Config;

$clsConfig = new Config();
$clsConfig->addDirectory(__DIR__ . '\config');
$clsConfig->setDriver('php');
$clsConfig->set('my', array('email'=>'junqing124@126.com'));
$clsConfig->init();
print_r($clsConfig->get());
print_r($clsConfig->get('app.default_timezone'));
print_r($clsConfig->get('my.email'));
