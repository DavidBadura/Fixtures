<?php

namespace DavidBadura\Fixtures\Event;

use Symfony\Component\EventDispatcher\Event;
use DavidBadura\Fixtures\FixtureManager\FixtureManagerInterface;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class FixtureEvent extends Event
{

    /**
     *
     * @var FixtureManagerInterface
     */
    private $fixtureManager;

    /**
     *
     * @var array
     */
    private $options;

    /**
     *
     * @param FixtureManagerInterface $fixtureManager
     * @param array                   $options
     */
    public function __construct(FixtureManagerInterface $fixtureManager, array $options = array())
    {
        $this->fixtureManager = $fixtureManager;
        $this->options = $options;
    }

    /**
     *
     * @return FixtureManagerInterface
     */
    public function getFixtureManager()
    {
        return $this->fixtureManager;
    }

    /**
     *
     * @param  FixtureManagerInterface $fixtureManager
     * @return FixtureEvent
     */
    public function setFixtureManager(FixtureManagerInterface $fixtureManager)
    {
        $this->fixtureManager = $fixtureManager;

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
     * @param  array        $options
     * @return FixtureEvent
     */
    public function setOptions(array $options)
    {
        $this->options = $options;

        return $this;
    }

}
