<?php

namespace DavidBadura\Fixtures\Loader;

use DavidBadura\Fixtures\Fixture\FixtureCollection;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class ChainLoader implements LoaderInterface
{

    /**
     *
     * @var LoaderInterface[]
     */
    private $loaders = array();

    /**
     *
     * @param LoaderInterface[] $loaders
     */
    public function __construct(array $loaders = array())
    {
        foreach ($loaders as $loader) {
            $this->add($loader);
        }
    }

    /**
     *
     * @param LoaderInterface $loader
     */
    public function add(LoaderInterface $loader)
    {
        $this->loaders[] = $loader;
    }

    /**
     *
     * @param  type              $path
     * @return FixtureCollection
     */
    public function load($path, array $options = array())
    {

        $collection = new FixtureCollection();

        foreach ($this->loaders as $loader) {
            $data = $loader->load($path, $options);
            $collection->merge($data);
        }

        return $collection;
    }

}
