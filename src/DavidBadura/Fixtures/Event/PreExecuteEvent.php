<?php

namespace DavidBadura\Fixtures\Event;

use Symfony\Component\EventDispatcher\Event;
use DavidBadura\Fixtures\Fixture\FixtureCollection;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class PreExecuteEvent extends Event
{

    /**
     *
     * @var FixtureCollection
     */
    private $collection;

    /**
     *
     * @var array
     */
    private $options;

    /**
     *
     * @param FixtureCollection $collection
     * @param array             $options
     */
    public function __construct(FixtureCollection $collection, array $options = array())
    {
        $this->collection = $collection;
        $this->options = $options;
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
     * @return \DavidBadura\Fixtures\Event\PreExecuteEvent
     */
    public function setFixtures(FixtureCollection $collection)
    {
        $this->collection = $collection;

        return $this;
    }

    /**
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     *
     * @param  array                                             $options
     * @return \DavidBadura\Fixtures\Event\PreExecuteEvent
     */
    public function setOptions(array $options)
    {
        $this->options = $options;

        return $this;
    }

}
