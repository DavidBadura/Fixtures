<?php

namespace DavidBadura\Fixtures\Fixture;

use DavidBadura\Fixtures\Exception\FixtureException;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class FixtureData
{
    /**
     *
     * @var string
     */
    protected $key;

    /**
     *
     * @var mixed
     */
    protected $data;

    /**
     *
     * @var object
     */
    protected $object;

    /**
     *
     * @var Fixture
     */
    protected $fixture;

    /**
     *
     * @var bool
     */
    private $loaded = false;

    /**
     *
     * @param string $key
     * @param mixed $data
     */
    public function __construct($key, $data)
    {
        $this->key = $key;
        $this->data = $data;
    }

    /**
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     *
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     *
     * @param  mixed $data
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     *
     * @param  object $object
     * @return $this
     */
    public function setObject($object)
    {
        if ($this->object) {
            throw new FixtureException("fixture data has already an object");
        }
        $this->object = $object;

        return $this;
    }

    /**
     *
     * @return object
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     *
     * @return bool
     */
    public function hasObject()
    {
        return ($this->object != null);
    }

    /**
     *
     * @return array
     */
    public function getProperties()
    {
        return $this->fixture->getProperties();
    }

    /**
     *
     * @param  Fixture $fixture
     * @throws FixtureException
     */
    public function setFixture(Fixture $fixture)
    {
        if ($this->fixture) {
            throw new FixtureException("Fixture data has a parent already");
        }
        $this->fixture = $fixture;
    }

    /**
     *
     * @return Fixture
     */
    public function getFixture()
    {
        return $this->fixture;
    }

    /**
     *
     * @param  boolean $loaded
     * @return $this
     */
    public function setLoaded($loaded = true)
    {
        $this->loaded = $loaded;

        return $this;
    }

    /**
     *
     * @return boolean
     */
    public function isLoaded()
    {
        return $this->loaded;
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function get($key)
    {
        if (!$this->has($key)) {
            return null;
        }

        return $this->data[$key];
    }

    /**
     * @param string $key
     * @return bool
     */
    public function has($key)
    {
        return isset($this->data[$key]);
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    public function set($key, $value)
    {
        $this->data[$key] = $value;
    }

    /**
     * @param string $key
     */
    public function remove($key)
    {
        if ($this->has($key)) {
            unset($this->data[$key]);
        }
    }
}
