<?php

namespace DavidBadura\Fixtures\Fixture;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class ParameterBag implements \ArrayAccess
{
    /**
     *
     * @var array
     */
    protected $parameters;

    /**
     *
     * @param array $parameters
     */
    public function __construct(array $parameters = array())
    {
        $this->parameters = $parameters;
    }

    /**
     *
     * @param  string $key
     * @return boolean
     */
    public function has($key)
    {
        return isset($this->parameters[$key]);
    }

    /**
     *
     * @param  string $key
     * @param  mixed $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        if (!$this->has($key)) {
            return $default;
        }

        return $this->parameters[$key];
    }

    /**
     *
     * @param string $key
     * @param mixed $value
     */
    public function set($key, $value)
    {
        $this->parameters[$key] = $value;
    }

    /**
     *
     * @param string $key
     */
    public function remove($key)
    {
        if ($this->has($key)) {
            unset($this->parameters[$key]);
        }
    }

    /**
     *
     * @return array
     */
    public function toArray()
    {
        return $this->parameters;
    }

    /**
     *
     * @param string $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return $this->has($offset);
    }

    /**
     *
     * @param string $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     *
     * @param string $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        $this->set($offset, $value);
    }

    /**
     *
     * @param string $offset
     */
    public function offsetUnset($offset)
    {
        $this->remove($offset);
    }
}
