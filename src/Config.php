<?php
declare(strict_types=1);


namespace DcrPHP\Config;


class Config extends ConfigInfo
{

    /**
     * Config constructor.
     * @param string $path 配置的文件或目录
     * @param string $driver
     * @throws \Exception
     */
    public function __construct($path = '', $driver = 'php')
    {
        if ($path) {
            if (!file_exists($path)) {
                throw new \Exception('没有找到文件或目录');
            }
            if (is_dir($path)) {
                $this->addDirectory($path);
            } else {
                $this->addFile($path);
            }
        }
        $this->setDriver($driver);
        $this->init();
    }

    /**
     * 修改配置 如 改app.php下的name则为 set('app.name','abc');
     * @param $name
     * @param $value
     */
    public function set($name, $value)
    {
        $configSplit = explode('.', $name);
        if (1 == count($configSplit)) {
            $this->config[$name] = $value;
        } else {
            return $this->setByDot($this->config, $name, $value);
        }
    }

    /**
     * 设置.分隔的配置 比如setByDot($config,'app.name','value')
     * @param array $data
     * @param $name
     * @param $value
     * @return array|mixed
     */
    private function setByDot(array &$data, $name, $value)
    {
        if ($name === null) {
            return $data = $value;
        }

        $keys = explode('.', $name);
        while (count($keys) > 1) {
            $name = array_shift($keys);
            if (!isset($data[$name]) || !is_array($data[$name])) {
                $data[$name] = [];
            }
            $data = &$data[$name];
        }
        $data[array_shift($keys)] = $value;
        return $data;
    }

    /**
     * 通过文件或目录的配置来获取配置
     * @param $path
     * @param $key
     * @throws \Exception
     */
    public static function getByFileOrDirectory($path, $key)
    {
        $clsConfig = new self($path);
        return $clsConfig->get($key);
    }

    /**
     * 设置配置
     * @param $configType
     * @param $config
     */
    public function setItem($configType, $config)
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
            $this->setItem($configType, $result);
        }
    }
}