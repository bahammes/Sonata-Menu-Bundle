<?php

namespace Prodigious\Sonata\MenuBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('prodigious_sonata_menu');
        $rootNode = $treeBuilder->getRootNode();
        /** @var ArrayNodeDefinition $rootNode */

        $rootNode
            ->children()
                ->arrayNode('entities')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('menu')->end()
                        ->scalarNode('menu_item')->end()
                    ->end()
                ->end()
                ->arrayNode('admins')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('menu')->end()
                        ->scalarNode('menu_item')->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
