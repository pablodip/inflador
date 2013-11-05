<?php

namespace Inflador;

use Symfony\Component\Yaml\Yaml;

class ConfigFileReader
{
    public function read($configFile)
    {
        if (!file_exists($configFile)) {
            throw new \RuntimeException(sprintf('The config file "%s" does not exist.', $configFile));
        }

        $config = Yaml::parse($configFile);
        if ($this->isConfigNotValid($config)) {
            throw new \RuntimeException(sprintf('The config file "%s" is not valid.', $configFile));
        }

        return $config;
    }

    private function isConfigNotValid($config)
    {
        return !is_array($config);
    }
}