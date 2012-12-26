<?php

namespace DavidBadura\Fixtures\EventListener;

use DavidBadura\Fixtures\Event\PostFixtureLoadEvent;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use DavidBadura\Fixtures\ConverterRepository;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class TreeListener
{

    /**
     *
     * @var ConverterRepository
     */
    protected $converterRepository;

    /**
     *
     * @param ConverterRepository $converterRepository
     */
    public function __construct(ConverterRepository $converterRepository)
    {
        $this->converterRepository = $converterRepository;
    }

    /**
     *
     * @return ConverterRepository
     */
    public function getConverterRepository()
    {
        return $this->converterRepository;
    }

    /**
     *
     * @param PostExecuteEvent $event
     */
    public function onPostFixtureLoad(PostFixtureLoadEvent $event)
    {
        $temp = $data = $event->getData();
        $tree = $this->getFixtureTree();

        $data = $tree->normalize($data);
        $data = $tree->finalize($data);

        foreach (array_keys($data) as $key) {
            $data[$key]['data'] = $temp[$key]['data'];
            $data[$key]['properties'] = (isset($temp[$key]['properties']) ? $temp[$key]['properties'] : array());
            $data[$key]['tags'] = (isset($temp[$key]['tags']) ? $temp[$key]['tags'] : array());
            $data[$key] = $this->validate($data[$key]);
        }

        $event->setData($data);
    }

    /**
     *
     * @return \Symfony\Component\Config\Definition\NodeInterface
     */
    protected function getFixtureTree()
    {
        $tree = new TreeBuilder();

        /* @var $root \Symfony\Component\Config\Definition\Builder\NodeBuilder */
        $root = $tree->root('fixtures');
        $root->useAttributeAsKey('type')->prototype('array')
            ->children()
                ->arrayNode('properties')->ignoreExtraKeys()->end()
                ->arrayNode('tags')->ignoreExtraKeys()->end()
                ->scalarNode('converter')->defaultValue('default')->end()
                ->arrayNode('validation')
                    ->children()
                        ->booleanNode('enable')->defaultValue(true)->end()
                        ->arrayNode('groups')->end()
                    ->end()
                ->end()
                ->arrayNode('data')->ignoreExtraKeys()->end()
            ->end()
        ->end()
        ;

        return $tree->buildTree();
    }

    protected function validate($data)
    {
        $converter = $this->converterRepository->getConverter($data['converter']);
        if (!$converter instanceof \DavidBadura\Fixtures\FixtureConverter\ConverterDataValidate) {
            return $data;
        }

        $builder = new TreeBuilder();
        $root = $builder->root('data');
        $node = $root->useAttributeAsKey('key')->prototype('array')->children();

        $converter->addNodeSchema($node);

        $tree = $builder->buildTree();

        $data['data'] = $tree->normalize($data['data']);
        $data['data'] = $tree->finalize($data['data']);

        return $data;
    }

}
