<?php

namespace DavidBadura\Fixtures\Event;

use DavidBadura\Fixtures\FixtureManager\FixtureManagerInterface;
use DavidBadura\Fixtures\Fixture\FixtureCollection;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class FixtureCollectionEvent extends FixtureEvent
{

    /**
     *
     * @var FixtureCollection
     */
    private $collection;


    /**
     *
     * @param FixtureCollection $collection
     * @param array             $options
     */
    public function __construct(FixtureManagerInterface $fixtureManager, FixtureCollection $collection, array $options = array())
    {
        parent::__construct($fixtureManager, $options);
        $this->collection = $collection;
    }

    /**
     *
     * @return FixtureCollection
     */
    public function getCollection()
    {
        return $this->collection;
    }

    /**
     *
     * @param  FixtureCollection $collection
     * @return FixtureCollectionEvent
     */
    public function setCollection(FixtureCollection $collection)
    {
        $this->collection = $collection;

        return $this;
    }

}
