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
            $this->addLoader($loader);
        }
    }

    protected function addLoader(LoaderInterface $loader)
    {
        $this->loaders[get_class($loader)] = $loader;
    }

    public function load($path)
    {

        $collection = new FixtureCollection();

        foreach ($this->loaders as $loader) {
            $data = $loader->load($path);
            $collection->merge($data);
        }

        return $collection;
    }

}