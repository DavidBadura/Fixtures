<?php

namespace DavidBadura\Fixtures\Loader;

use DavidBadura\Fixtures\Fixture\FixtureCollection;
use Symfony\Component\Finder\Finder;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class DirectoryLoader implements LoaderInterface
{

    /**
     *
     * @var LoaderInterface
     */
    private $loader;

    /**
     *
     * @param LoaderInterface $loaders
     */
    public function __construct(LoaderInterface $loader)
    {
        $this->loader = $loader;
    }

    /**
     *
     * @param string|array $path
     * @return FixtureCollection
     */
    public function load($path, array $options = array())
    {
        $finder = new Finder();
        $finder->in($path)->files();

        $collection = new FixtureCollection();

        foreach($finder as $file) {
            $col = $this->loader->load($file->getPathname(), $options);
            $collection->merge($col);
        }

        return $collection;
    }

}