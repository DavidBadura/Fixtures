<?php declare(strict_types=1);

namespace DavidBadura\Fixtures\Loader;

use DavidBadura\Fixtures\Fixture\FixtureCollection;

/**
 * @author David Badura <d.badura@gmx.de>
 */
class FilterLoader implements LoaderInterface
{
    protected $loader;

    public function __construct(LoaderInterface $loader)
    {
        $this->loader = $loader;
    }

    public function load($path, array $options = []): FixtureCollection
    {
        $collection = $this->loader->load($path, $options);

        if (!isset($options['tags'])) {
            return $collection;
        }

        $filter = $options['tags'];

        if (!is_array($filter)) {
            $filter = [$filter];
        }

        if (empty($filter)) {
            return $collection;
        }

        foreach ($collection as $fixture) {
            $tags = $fixture->getProperties()->get('tags');

            if (!$tags || !is_array($tags)) {
                $collection->remove($fixture->getName());
                continue;
            }

            foreach ($tags as $tag) {
                if (in_array($tag, $filter)) {
                    continue 2;
                }
            }
            $collection->remove($fixture->getName());
        }

        return $collection;
    }
}
