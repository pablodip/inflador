<?php

namespace Inflador;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Inflador\DependencyInjection\Container;
use Inflador\Plugin\Plugin;

class Inflador extends Application
{
    const VERSION = '0.1';

    const SOURCE_DIR_OPTION = 'source-dir';
    const DESTINATION_DIR_OPTION = 'destination-dir';

    const DEFAULT_DESTINATION_DIR = '_site';

    const CONFIG_FILE_NAME = 'inflador.yml';

    private $input;
    private $container;

    /**
     * {@inheritdoc}
     */
    public function __construct()
    {
        parent::__construct('Inflador', self::VERSION);

        $this->addCommands(array(
            new Command\AboutCommand(),
            new Command\ProcessCommand()
        ));
    }

    protected function getDefaultInputDefinition()
    {
        $definition = parent::getDefaultInputDefinition();

        $definition->addOptions(array(
            $this->createInputSourceDirOption(),
            $this->createInputDestinationDirOption()
        ));

        return $definition;
    }

    private function createInputSourceDirOption()
    {
        return new InputOption(self::SOURCE_DIR_OPTION, null, InputOption::VALUE_OPTIONAL, 'The source dir (default current)');
    }

    private function createInputDestinationDirOption()
    {
        return new InputOption(self::DESTINATION_DIR_OPTION, null, InputOption::VALUE_OPTIONAL, sprintf('The destination dir (default "%s/_site")', self::SOURCE_DIR_OPTION));
    }

    public function doRun(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;

        return parent::doRun($input, $output);
    }

    public function getContainer()
    {
        if ($this->container === null) {
            $this->container = $this->buildContainer();
        }

        return $this->container;
    }

    private function buildContainer()
    {
        $config = $this->readConfig();

        $container = new Container($this->buildPluginsFromConfig($config));
        $container->setParameter('source.dir', $this->getSourceDir());
        $container->setParameter('destination.dir', $this->getDestinationDir());
        $container->loadConfigFile($this->getConfigFile());
        $container->compile();

        return $container;
    }

    private function readConfig()
    {
        $reader = new ConfigFileReader();

        return $reader->read($this->getConfigFile());
    }

    private function buildPluginsFromConfig($config)
    {
        return $this->buildPlugins($this->getPluginsConfigFromConfig($config));
    }

    private function getPluginsConfigFromConfig($config)
    {
        if ($this->isPluginsConfigNotValid($config)) {
            throw new \RuntimeException('The plugins config does not exist or it is not valid.');
        }

        return $config['plugins'];
    }

    private function isPluginsConfigNotValid($config)
    {
        return !isset($config['plugins']) || !is_array($config['plugins']);
    }

    private function buildPlugins($pluginsConfig)
    {
        $plugins = array();
        foreach ($pluginsConfig as $pluginClass) {
            $plugins[] = $this->buildPlugin($pluginClass);
        }

        return $plugins;
    }

    private function buildPlugin($pluginClass)
    {
        $plugin = new $pluginClass();

        if (!$plugin instanceof Plugin) {
            throw new \RuntimeException(sprintf('The plugin "%s" is not an instance of "Inflador\Plugin\Plugin".', $pluginClass));
        }

        return $plugin;
    }

    private function getConfigFile()
    {
        return $this->getSourceDir().DIRECTORY_SEPARATOR.self::CONFIG_FILE_NAME;
    }

    private function getSourceDir()
    {
        return $this->input->getOption(self::SOURCE_DIR_OPTION) ?:
               $this->getDefaultSourceDir();
    }

    private function getDefaultSourceDir()
    {
        return getcwd();
    }

    private function getDestinationDir()
    {
        return $this->input->getOption(self::DESTINATION_DIR_OPTION) ?:
               $this->getDefaultDestinationDir();
    }

    private function getDefaultDestinationDir()
    {
        return $this->getSourceDir().
               DIRECTORY_SEPARATOR.
               self::DEFAULT_DESTINATION_DIR;
    }
}