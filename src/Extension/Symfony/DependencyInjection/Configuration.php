<?php declare(strict_types=1);

namespace DavidBadura\Fixtures\Extension\Symfony\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\NodeInterface;

/**
 * @author David Badura <d.badura@gmx.de>
 */
class Configuration
{
    public function getConfigTree(): NodeInterface
    {
        $treeBuilder = new TreeBuilder();

        $rootNode = $treeBuilder->root('david_badura_fixtures');

        $rootNode
            ->children()
                ->arrayNode('bundles')
                    ->prototype('scalar')->isRequired()->end()
                    ->defaultValue([])
                ->end()
                ->scalarNode('persister')->defaultValue('orm')->end()
                ->scalarNode('persister_id')->defaultValue(null)->end()
                ->arrayNode('defaults')
                    ->children()
                        ->scalarNode('converter')->defaultValue('default')->end()
                        ->arrayNode('validation')
                            ->children()
                                ->scalarNode('enable')->defaultValue(true)->end()
                                ->scalarNode('group')->defaultValue('Default')->end()
                            ->end()
                        ->end()
                        ->arrayNode('parameters')->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder->buildTree();
    }
}
