<?php

namespace DavidBadura\Fixtures\Loader;

use DavidBadura\Fixtures\Fixture\FixtureCollection;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class ArrayLoader implements LoaderInterface
{
    /**
     *
     * @var LoaderInterface
     */
    protected $loader;

    /**
     *
     * @param LoaderInterface $loader
     */
    public function __construct(LoaderInterface $loader)
    {
        $this->loader = $loader;
    }

    /**
     *
     * @param mixed $path
     * @param array $options
     * @return FixtureCollection
     */
    public function load($path, array $options = array())
    {
        if (!is_array($path)) {
            return $this->loader->load($path, $options);
        }

        $collection = new FixtureCollection();

        foreach ($path as $p) {
            $c = $this->loader->load($p, $options);
            $collection->merge($c);
        }

        return $collection;
    }
}
