<?php

namespace Dark\TranslationBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @author Evgeniy Guseletov <d46k16@gmail.com>
 */
class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('dark_translation');

        $rootNode
            ->children()
                ->arrayNode('source')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('base_dir')->defaultValue('%kernel.root_dir%/../docs')->end()
                        ->scalarNode('from')->defaultValue('%kernel.root_dir%/../docs/symfony-docs')->end()
                        ->scalarNode('to')->defaultValue('%kernel.root_dir%/../docs/symfony-docs-ru')->end()
                    ->end()
                ->end()
            ->end()
            ->children()
                ->arrayNode('repositories')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('en')->defaultValue('https://github.com/symfony/symfony-docs')->end()
                        ->scalarNode('fr')->defaultValue('https://github.com/gscorpio/symfony-docs-fr')->end()
                        ->scalarNode('it')->defaultValue('https://github.com/garak/symfony-docs-it')->end()
                        ->scalarNode('ja')->defaultValue('https://github.com/symfony-japan/symfony-docs-ja')->end()
                        ->scalarNode('pl')->defaultValue('https://github.com/ampluso/symfony-docs-pl')->end()
                        ->scalarNode('ro')->defaultValue('https://github.com/sebio/symfony-docs-ro')->end()
                        ->scalarNode('ru')->defaultValue('https://github.com/avalanche123/symfony-docs-ru')->end()
                        ->scalarNode('es')->defaultValue('https://github.com/gitnacho/symfony-docs-es')->end()
                        ->scalarNode('tr')->defaultValue('https://github.com/symfony-tr/symfony-docs-tr')->end()
                    ->end()
                ->end()
            ->end()
            ->children()
                ->arrayNode('build')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('path')->defaultValue('%kernel.root_dir%/../docs/build')->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
