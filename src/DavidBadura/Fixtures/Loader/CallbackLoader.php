<?php

namespace DavidBadura\Fixtures\Loader;

use DavidBadura\Fixtures\Exception\FixtureException;
use DavidBadura\Fixtures\Fixture\FixtureCollection;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class CallbackLoader implements LoaderInterface
{
    /**
     *
     * @var callable
     */
    protected $callback;

    /**
     *
     * @param callable $callback
     */
    public function __construct($callback)
    {
        $this->callback = $callback;
    }

    /**
     *
     * @param  mixed $path
     * @param array $options
     * @return FixtureCollection
     */
    public function load($path, array $options = array())
    {
        $collection = $this->callback($path, $options);

        if (is_array($collection)) {
            return FixtureCollection::create($collection);
        } elseif ($collection instanceof FixtureCollection) {
            return $collection;
        }

        throw new FixtureException('the callback function must return a FixtureCollection instance or a fixture array');
    }
}
