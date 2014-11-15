<?php

namespace DavidBadura\Fixtures\Fixture;

use DavidBadura\Fixtures\Converter\ConverterInterface;
use DavidBadura\Fixtures\Exception\FixtureException;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class Fixture implements \IteratorAggregate, \Countable
{
    /**
     *
     * @var array
     */
    protected static $defaultParameters = array();

    /**
     *
     * @var string
     */
    protected static $defaultConverter = 'default';

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
     * @param string $name
     * @param string $converter
     * @param ParameterBag $properties
     */
    public function __construct($name, $converter = null, ParameterBag $properties = null)
    {
        $this->name = $name;
        $this->converter = ($converter) ?: self::$defaultConverter;

        $params = ($properties) ? $properties->toArray() : array();
        $this->properties = new ParameterBag(array_merge(self::$defaultParameters, $params));
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
     *
     * @return ConverterInterface
     */
    public function getConverter()
    {
        return $this->converter;
    }

    /**
     *
     * @param  string $key
     * @return boolean
     */
    public function has($key)
    {
        return isset($this->fixtureData[$key]);
    }

    /**
     *
     * @param  string $key
     * @return FixtureData
     * @throws FixtureException
     */
    public function get($key)
    {
        if (!$this->has($key)) {
            throw new FixtureException(sprintf('Fixture data with key "%s" does not exist in "%s" fixture', $key,
                    $this->name));
        }

        return $this->fixtureData[$key];
    }

    /**
     *
     * @param  FixtureData $fixtureData
     * @return $this
     * @throws FixtureException
     */
    public function add(FixtureData $fixtureData)
    {
        $key = $fixtureData->getKey();
        if ($this->has($key)) {
            throw new FixtureException(sprintf('fixture data with key "%s" already exists in "%s" fixture', $key,
                    $this->name));
        }

        $this->fixtureData[$key] = $fixtureData;
        $fixtureData->setFixture($this);

        return $this;
    }

    /**
     *
     * @param  FixtureData $fixtureData
     * @return $this
     */
    public function remove(FixtureData $fixtureData)
    {
        $key = $fixtureData->getKey();
        if ($this->has($key)) {
            unset($this->fixtureData[$key]);
        }

        return $this;
    }

    /**
     *
     * @return ParameterBag
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     *
     * @param  ParameterBag $properties
     * @return $this
     */
    public function setProperties(ParameterBag $properties)
    {
        $this->properties = $properties;

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

    /**
     *
     * @param  string $name
     * @param  array $data
     * @return Fixture
     * @throws FixtureException
     */
    public static function create($name, array $data)
    {
        $converter = (isset($data['converter'])) ? $data['converter'] : 'default';
        $fixture = new self($name, $converter);

        if (!isset($data['data'])) {
            throw new FixtureException("missing data property");
        }

        foreach ($data['data'] as $key => $value) {
            $fixture->add(new FixtureData($key, $value));
        }

        if (isset($data['properties'])) {
            $fixture->setProperties(new ParameterBag($data['properties']));
        }

        return $fixture;
    }

    /**
     *
     * @param array $params
     */
    public static function setDefaultParameters(array $params = array())
    {
        self::$defaultParameters = $params;
    }

    /**
     *
     * @return array
     */
    public static function getDefaultParameters()
    {
        return self::$defaultParameters;
    }

    /**
     *
     * @param string $converter
     */
    public static function setDefaultConverter($converter)
    {
        self::$defaultConverter = $converter;
    }

    /**
     *
     * @return string
     */
    public static function getDefaultConverter()
    {
        return self::$defaultConverter;
    }
}
