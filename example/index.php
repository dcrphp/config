<?php
require_once("../vendor/autoload.php");

use DcrPHP\Config\Config;
use DcrPHP\Config\ConfigFactory;

ini_set('display_errors', 'on');

try {
    $clsConfig = new Config(__DIR__ . '\config');

    //获取全部
    echo 'item1:';
    print_r($clsConfig->get());
    echo "\r\n";

    //获取app配置下的default_timezone
    echo 'item2:';
    print_r($clsConfig->get('app.default_timezone'));
    echo "\r\n";

    $clsConfig->set('my', array('email' => 'junqing124@126.com'));
    //获取自己设置的my下的email
    echo 'item3:';
    print_r($clsConfig->get('my.email'));
    echo "\r\n";

    //通过文件或目录直接获取配置
    echo 'item4:';
    print_r(ConfigFactory::fromFile(__DIR__ . '\config\app.php', 'app.session_life_time'));
    echo "\r\n";

    //设置某个配置
    //配置前
    echo 'item5:';
    print_r($clsConfig->get('cache.memcache.host'));
    echo "\r\n";
    $clsConfig->set('cache.memcache.host', 'memcache-host');
    //配置后
    echo 'item6:';
    print_r($clsConfig->get('cache.memcache.host'));
} catch (Exception $e) {
}
