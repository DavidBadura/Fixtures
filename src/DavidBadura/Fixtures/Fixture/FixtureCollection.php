<?php

namespace DavidBadura\Fixtures\Fixture;

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
        if (isset($this->fixtures[$name])) {
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
        if (!isset($this->fixtures[$name])) {
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
        if (isset($this->fixtures[$name])) {
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

    /**
     *
     * @param FixtureCollection $collection
     */
    public function merge(FixtureCollection $collection)
    {
        foreach($collection as $fixture) {
            $this->add($fixture);
        }
    }

    /**
     *
     * @param array $data
     * @return FixtureCollection
     */
    public static function create(array $data)
    {
        $collection = new self();
        foreach ($data as $name => $info) {
            $collection->add(Fixture::create($name, $info));
        }

        return $collection;
    }

}