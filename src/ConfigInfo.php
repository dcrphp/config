<?php
declare(strict_types=1);

namespace DcrPHP\Config;

use DcrPHP\Config\Driver\Php;
use DcrPHP\Config\Driver\Ini;
use DcrPHP\Config\Driver\Xml;
use DcrPHP\Config\Driver\Json;
use DcrPHP\Config\Driver\Yml;
use Noodlehaus\Config as NConfig;

class ConfigInfo
{
    protected $config = array(); //配置详情
    protected $files = array(); //配置文件列表
    protected $driverClass; //实例化的驱动类
    protected $driverName;

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
     * @throws \Exception
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
        return pathinfo($filePath, PATHINFO_FILENAME);
    }
}