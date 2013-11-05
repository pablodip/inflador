<?php

namespace Inflador\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;

class Container extends ContainerBuilder
{
    public function __construct(array $plugins)
    {
        parent::__construct();

        $this->registerExtension(new PluginsExtension());

        $this->registerPluginsExtensions($plugins);
        $this->callPluginsBuildContainerMethod($plugins);
    }

    private function registerPluginsExtensions($plugins)
    {
        foreach ($plugins as $plugin) {
            $this->registerPluginExtension($plugin);
        }
    }

    private function registerPluginExtension($plugin)
    {
        $extension = $plugin->getContainerExtension();
        if ($extension) {
            $this->registerExtension($extension);
        }
    }

    private function callPluginsBuildContainerMethod($plugins)
    {
        foreach ($plugins as $plugin) {
            $plugin->buildContainer($this);
        }
    }

    public function loadConfigFile($configFile)
    {
        $loader = new YamlFileLoader($this, new FileLocator(dirname($configFile)));
        $loader->load(basename($configFile));
    }
}