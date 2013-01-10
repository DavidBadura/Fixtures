<?php

namespace DavidBadura\Fixtures\Loader;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class LoaderChain
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
        $fixtures = array();

        foreach ($this->loaders as $loader) {
            $data = $loader->load($path);
            $fixtures = array_merge_recursive($fixtures, $data);
        }

        return $fixtures;
    }

}