<?php declare(strict_types=1);

namespace DavidBadura\Fixtures\Loader;

use DavidBadura\Fixtures\Fixture\FixtureCollection;

/**
 * @author David Badura <d.badura@gmx.de>
 */
class ChainLoader implements LoaderInterface
{
    private $loaders = [];

    public function __construct(array $loaders = [])
    {
        foreach ($loaders as $loader) {
            $this->add($loader);
        }
    }

    public function add(LoaderInterface $loader): void
    {
        $this->loaders[] = $loader;
    }

    public function load($path, array $options = []): FixtureCollection
    {
        $collection = new FixtureCollection();

        foreach ($this->loaders as $loader) {
            $data = $loader->load($path, $options);
            $collection->merge($data);
        }

        return $collection;
    }
}
