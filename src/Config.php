<?php
declare(strict_types=1);

namespace DcrPHP\Config;

use DcrPHP\Config\Driver\Php;
use DcrPHP\Config\Driver\Ini;
use DcrPHP\Config\Driver\Xml;
use DcrPHP\Config\Driver\Json;
use DcrPHP\Config\Driver\Yml;
use Noodlehaus\Config as NConfig;

class Config
{
    private $config = array(); //配置详情
    private $files = array(); //配置文件列表
    private $driverClass; //实例化的驱动类

    /**
     * 添加配置文件的路径
     * @param $filePath
     * @return bool
     */
    public function addFile($filePath): bool
    {
        if (file_exists($filePath)) {
            $this->files[] = $filePath;
        }
        return true;
    }

    /**
     * 添加配置文件的路径 子目录也会加进来
     * @param $directory
     * @return bool
     */
    public function addDirectory($directory): bool
    {
        if (file_exists($directory)) {
            $fileList = scandir($directory);
            foreach ($fileList as $fileName) {
                if (!in_array($fileName, array('.', '..'))) {
                    $filePath = $directory . DIRECTORY_SEPARATOR . $fileName;
                    if (is_dir($filePath)) {
                        return $this->addDirectory($filePath);
                    } else {
                        $this->addFile($filePath);
                    }
                }
            }
        }
        return true;
    }

    /**
     * 这里用来设置driver 用的hassankhan/config 可以用很多格式的配置文件，所以这里只判断存在不存在 后面扩展驱动类在这里写
     * @param $driverName
     * @return bool
     */
    public function setDriver($driverName): bool
    {
        $this->driverName = $driverName;
        $driverName = ucfirst($driverName);
        $className = "DcrPHP\\Config\\Driver\\{$driverName}";
        if (!class_exists($className)) {
            throw new \Exception('驱动类不支持');
        }
        $clsDriver = new $className;
        $this->driverClass = $clsDriver;
        return true;
    }

    /**
     * 通过文件名得到配置名
     * @param $filePath
     * @return string
     */
    public function getAssertName($filePath): string
    {
        $configType = pathinfo($filePath, PATHINFO_FILENAME);
        return $configType;
    }

    /**
     * 设置配置
     * @param $configType
     * @param $config
     */
    public function set($configType, $config)
    {
        $this->config[$configType] = $config;
        //dd($this);
    }

    /**
     * 根据key获取配置
     * @param string $name
     * @return array|mixed|string
     */
    public function get($name = '')
    {
        if (empty($name)) {
            return $this->config;
        }

        //返回配置
        $configSplit = explode('.', $name);
        if (count($configSplit) < 2) {
            // app
            return $this->config[$name];
        }
        if (empty($configSplit[1])) {
            // app.
            return $this->config[$configSplit[0]];
        }

        //database.mysql.main.driver
        $value = "";
        foreach ($configSplit as $configName) {
            if (empty($configName)) {
                break;
            }
            if (empty($value)) {
                $value = $this->config[$configName];
                if (empty($value)) {
                    //防止死循环
                    $value = 'not config';
                    break;
                }
            } else {
                $value = $value[$configName];
            }
        }
        return $value == 'not config' ? '' : $value;
    }

    public function init()
    {
        foreach ($this->files as $filePath) {
            $this->driverClass->setFilePath($filePath);
            $result = $this->driverClass->parse();
            $configType = $this->getAssertName($filePath);
            $this->set($configType, $result);
        }
    }
}