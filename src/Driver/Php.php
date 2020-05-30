<?php
declare(strict_types=1);

namespace DcrPHP\Config\Driver;
use DcrPHP\Config\Concerns\Config as IConfig;

class Php extends IConfig
{
    public function parse()
    {
        // TODO: Implement parse() method.
        return include $this->filePath;
    }
}