<?php

namespace Inflador\Plugin\InfladorPlugin\DependencyInjection;

use Inflador\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;

class InfladorExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../config'));
        $loader->load('inflador.yml');

        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);

        $this->setConfigParametersToContainer($config, $container);
    }

    private function setConfigParametersToContainer($config, $container)
    {
        foreach ($this->getConfigParameters($config) as $name => $value) {
            $container->setParameter($name, $value);
        }
    }

    private function getConfigParameters($config)
    {
        return array(
            'url'                            => $config['url'],
            'path'                           => $config['path'],
            'destination_dir_clean_excludes' => $this->getDestinationDirCleanExcludes($config),
            'static.excludes'                => $this->getStatic($config, 'excludes'),
            'static.extensions'              => $this->getStatic($config, 'extensions'),
            'static.explicits'               => $this->getStatic($config, 'explicits')
        );
    }

    private function getDestinationDirCleanExcludes($configs)
    {
        return isset($config['destination_dir_clean_excludes']) ?
               $config['destination_dir_clean_excludes'] :
               array();
    }

    private function getStatic($configs, $name)
    {
        return isset($configs['static'][$name]) ?
               $configs['static'][$name] :
               array();
    }
}