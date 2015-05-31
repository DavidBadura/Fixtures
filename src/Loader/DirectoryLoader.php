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
     * @param LoaderInterface $loader
     */
    public function __construct(LoaderInterface $loader)
    {
        $this->loader = $loader;
    }

    /**
     *
     * @param  string|array $path
     * @param array $options
     * @return FixtureCollection
     */
    public function load($path, array $options = array())
    {
        if (!file_exists($path)) {
            throw new \RuntimeException(sprintf('"%s" dir or file not found', $path));
        }

        if (is_file($path)) {
            return $this->loader->load($path, $options);
        }

        $finder = new Finder();
        $finder->in($path)->files();

        $collection = new FixtureCollection();

        foreach ($finder as $file) {
            $col = $this->loader->load(realpath($file->getPathname()), $options);
            $collection->merge($col);
        }

        return $collection;
    }
}
