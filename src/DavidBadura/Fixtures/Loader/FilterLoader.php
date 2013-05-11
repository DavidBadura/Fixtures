<?php

namespace DavidBadura\Fixtures\Loader;

use DavidBadura\Fixtures\Fixture\FixtureCollection;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class FilterLoader implements LoaderInterface
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
     * @param  string            $path
     * @return FixtureCollection
     */
    public function load($path, array $options = array())
    {
        $collection = $this->loader->load($path, $options);

        if (!isset($options['tags'])) {
            return $collection;
        }

        $filter = $options['tags'];

        if (!is_array($filter)) {
            $filter = array($filter);
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
