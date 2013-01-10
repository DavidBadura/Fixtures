<?php

namespace DavidBadura\Fixtures;

use DavidBadura\Fixtures\Exception\FixtureException;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class Fixture implements \IteratorAggregate, \Countable
{

    /**
     *
     * @var string
     */
    private $name;

    /**
     *
     * @var array
     */
    private $converter;

    /**
     *
     * @var array
     */
    private $properties = array();

    /**
     *
     * @var FixtureData[]
     */
    private $fixtureData = array();

    /**
     *
     * @param string                    $name
     * @param ConverterInterface $converter
     * @param type                      $persister
     * @param array                     $data
     */
    public function __construct($name, $converter, array $properties = array())
    {
        $this->name = $name;
        $this->converter = $converter;
        $this->properties = $properties;
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**


    /**
     *
     * @return ConverterInterface
     */
    public function getConverter()
    {
        return $this->converter;
    }

    /**
     *
     * @param  string  $key
     * @return boolean
     */
    public function hasFixtureData($key)
    {
        return isset($this->fixtureData[$key]);
    }

    /**
     *
     * @param  string           $key
     * @return FixtureData
     * @throws FixtureException
     */
    public function getFixtureData($key)
    {
        if (!$this->hasFixtureData($key)) {
            throw new FixtureException(sprintf('Fixture data with key "%s" does not exist in "%s" fixture', $key, $this->name));
        }

        return $this->fixtureData[$key];
    }

    /**
     *
     * @param  FixtureData                         $fixtureData
     * @return \DavidBadura\Fixtures\Fixture
     * @throws FixtureException
     */
    public function addFixtureData(FixtureData $fixtureData)
    {
        $key = $fixtureData->getKey();
        if ($this->hasFixtureData($key)) {
            throw new FixtureException(sprintf('fixture data with key "%s" already exists in "%s" fixture', $key, $this->name));
        }

        $this->fixtureData[$key] = $fixtureData;
        $fixtureData->setFixture($this);

        return $this;
    }

    /**
     *
     * @param  FixtureData                         $fixtureData
     * @return \DavidBadura\Fixtures\Fixture
     */
    public function removeFixtureData(FixtureData $fixtureData)
    {
        $key = $fixtureData->getKey();
        if ($this->hasFixtureData($key)) {
            unset($this->fixtureData[$key]);
        }

        return $this;
    }

    /**
     *
     * @return array
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     *
     * @param  array                               $properties
     * @return \DavidBadura\Fixtures\Fixture
     */
    public function setProperties(array $properties)
    {
        $this->properties =  $properties;

        return $this;
    }

    /**
     *
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->fixtureData);
    }

    /**
     *
     * @return int
     */
    public function count()
    {
        return count($this->fixtureData);
    }


    static public function create($name, array $data)
    {
        $converter = (isset($data['converter'])) ? $data['converter'] : 'default' ;
        $fixture = new Fixture($name, $converter);

        if (!isset($data['data'])) {
            throw new FixtureException("missing data property");
        }

        foreach ($data['data'] as $key => $value) {
            $fixture->addFixtureData(new FixtureData($key, $value));
        }

        if (isset($data['properties'])) {
            $fixture->setProperties($data['properties']);
        }

        return $fixture;
    }

}
