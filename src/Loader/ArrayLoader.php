<?php declare(strict_types=1);

namespace DavidBadura\Fixtures\Loader;

use DavidBadura\Fixtures\Fixture\FixtureCollection;

/**
 * @author David Badura <d.badura@gmx.de>
 */
class ArrayLoader implements LoaderInterface
{
    protected $loader;

    public function __construct(LoaderInterface $loader)
    {
        $this->loader = $loader;
    }

    public function load($path, array $options = []): FixtureCollection
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
