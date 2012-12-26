<?php

namespace DavidBadura\Fixtures;

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
    protected $dirty;

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
     * @var int
     */
    private $order;

    /**
     *
     * @var boolean
     */
    private $loaded = false;

    /**
     *
     * @var array
     */
    private $params = array();

    /**
     *
     * @param string $key
     * @param mixed  $data
     */
    public function __construct($key, $data)
    {
        $this->key = $key;
        $this->data = $data;
        $this->dirty = $data;
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
     * @param  mixed        $data
     * @return \FixtureData
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     *
     * @return mixed
     */
    public function getDirtyData()
    {
        return $this->dirty;
    }

    /**
     *
     * @param  object                                  $object
     * @return \DavidBadura\Fixtures\FixtureData
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
     * @return boolean
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
     * @param  Fixture          $fixture
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
     * @param  int                                 $order
     * @return \DavidBadura\Fixtures\Fixture
     */
    public function setOrder($order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     *
     * @return int
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     *
     * @param  boolean                                 $loaded
     * @return \DavidBadura\Fixtures\FixtureData
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
     *
     * @param  string $key
     * @return mixed
     */
    public function getParam($key)
    {
        if (!isset($this->params[$key])) {
            return null;
        }

        return $this->params[$key];
    }

    /**
     *
     * @param  string  $key
     * @return boolean
     */
    public function hasParam($key)
    {
        return isset($this->params[$key]);
    }

    /**
     *
     * @param string $key
     * @param string $value
     */
    public function setParam($key, $value)
    {
        $this->params[$key] = $value;
    }

}
