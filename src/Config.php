<?php
declare(strict_types=1);


namespace DcrPHP\Config;


class Config extends ConfigInfo
{

    /**
     * Config constructor.
     * @param $path 配置的文件或目录
     * @param string $driver
     * @throws \Exception
     */
    public function __construct($path, $driver = 'php')
    {
        if (!file_exists($path)) {
            throw new \Exception('没有找到文件或目录');
        }
        if (is_dir($path)) {
            $this->addDirectory($path);
        } else {
            $this->addFile($path);
        }
        $this->setDriver($driver);
        $this->init();
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

    /**
     * 初始化
     */
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