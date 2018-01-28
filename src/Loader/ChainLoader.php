<?php declare(strict_types=1);

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
    private $loaders = [];

    /**
     *
     * @param LoaderInterface[] $loaders
     */
    public function __construct(array $loaders = [])
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
     * @param  string|array $path
     * @param array $options
     * @return FixtureCollection
     */
    public function load($path, array $options = [])
    {
        $collection = new FixtureCollection();

        foreach ($this->loaders as $loader) {
            $data = $loader->load($path, $options);
            $collection->merge($data);
        }

        return $collection;
    }
}
