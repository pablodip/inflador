<?php

namespace Inflador\Plugin\InfladorPlugin\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('inflador');

        $rootNode
            ->children()
                ->scalarNode('url')->isRequired()->end()
                ->scalarNode('path')->isRequired()->end()
                ->arrayNode('destination_dir_clean_excludes')
                    ->prototype('scalar')->end()
                ->end()
            ->end();

        $this->addStaticSection($rootNode);

        return $treeBuilder;
    }

    private function addStaticSection(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('static')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('excludes')
                            ->prototype('scalar')->end()
                        ->end()
                        ->arrayNode('extensions')
                            ->prototype('scalar')->end()
                        ->end()
                        ->arrayNode('explicits')
                            ->prototype('scalar')->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }
}