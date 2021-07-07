<?php

namespace Oka\Notifier\ClientBundle\DependencyInjection;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

/**
 * @author Cedrick Oka Baidai <okacedrick@gmail.com>
 */
class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('oka_notifier_client');
        /** @var \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition $rootNode */
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('service_name')
                    ->cannotBeEmpty()
                    ->defaultValue('notifier')
                ->end()
                ->scalarNode('logger_id')
                    ->cannotBeEmpty()
                    ->defaultValue('logger')
                ->end()
            ->end();

        return $treeBuilder;
    }
}
