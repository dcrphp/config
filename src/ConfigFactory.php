<?php
declare(strict_types=1);


namespace DcrPHP\Config;


class ConfigFactory
{
    /**
     * 通过文件或目录的配置来获取配置
     * @param $path
     * @param $key
     * @return
     * @throws \Exception
     */
    public static function getByFileOrDirectory($path, $key)
    {
        $clsConfig = new Config($path);
        return $clsConfig->get($key);
    }
}