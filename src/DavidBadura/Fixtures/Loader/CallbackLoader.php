<?php

namespace DavidBadura\Fixtures\Loader;

use DavidBadura\Fixtures\Fixture\FixtureCollection;
use DavidBadura\Fixtures\Exception\RuntimeException;

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
    function __construct($callback)
    {
        $this->callback = $callback;
    }

    /**
     *
     * @param  mixed     $path
     * @return FixtureCollection
     */
    public function load($path, array $options = array())
    {
        $collection = $this->callback($path, $options);

        if(is_array($collection)) {
            return FixtureCollection::create($collection);
        } elseif($collection instanceof FixtureCollection) {
            return $collection;
        }

        throw new RuntimeException('the callback function must return a FixtureCollection instance or a fixture array');
    }

}