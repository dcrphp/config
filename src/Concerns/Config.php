<?php

namespace DcrPHP\Config\Concerns;

abstract class Config
{
    protected $filePath;

    public abstract function parse();

    public function setFilePath($filePath)
    {
        $this->filePath = $filePath;
    }
}