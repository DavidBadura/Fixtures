<?php

namespace DavidBadura\Fixtures;

use DavidBadura\Fixtures\Exception\FixtureException;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class FixtureCollection implements \IteratorAggregate, \Countable
{

    /**
     *
     * @var array
     */
    protected $fixtures = array();

    /**
     *
     * @param array $fixtures
     */
    public function __construct(array $fixtures = array())
    {
        foreach ($fixtures as $fixture) {
            $this->add($fixture);
        }
    }

    /**
     *
     * @param  Fixture                                       $fixture
     * @return \DavidBadura\Fixtures\FixtureCollection
     * @throws FixtureException
     */
    public function add(Fixture $fixture)
    {
        $name = $fixture->getName();
        if ($this->has($name)) {
            throw new FixtureException(sprintf('fixture with the name "%s" already exists', $name));
        }
        $this->fixtures[$name] = $fixture;

        return $this;
    }

    /**
     *
     * @param  string  $name
     * @return Fixture
     */
    public function get($name)
    {
        if (!$this->has($name)) {
            return null;
        }

        return $this->fixtures[$name];
    }

    /**
     *
     * @param  string  $name
     * @return boolean
     */
    public function has($name)
    {
        return isset($this->fixtures[$name]);
    }

    /**
     *
     * @param  string                                        $name
     * @return \DavidBadura\Fixtures\FixtureCollection
     */
    public function remove($name)
    {
        if ($this->has($name)) {
            unset($this->fixtures[$name]);
        }

        return $this;
    }

    /**
     *
     * @return ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->fixtures);
    }

    /**
     *
     * @return int
     */
    public function count()
    {
        return count($this->fixtures);
    }

}
