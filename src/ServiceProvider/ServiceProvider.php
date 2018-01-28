<?php declare(strict_types=1);

namespace DavidBadura\Fixtures\ServiceProvider;

use DavidBadura\Fixtures\Exception\FixtureException;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class ServiceProvider implements ServiceProviderInterface
{
    /**
     *
     * @var array
     */
    protected $services = [];

    /**
     *
     * @param  string $name
     * @param  object $service
     * @return ServiceProvider
     */
    public function add($name, $service)
    {
        if ($this->has($name)) {
            throw new FixtureException(sprintf('Service with the name "%s" already exists', $name));
        }

        $this->services[$name] = $service;

        return $this;
    }

    /**
     *
     * @param  string $name
     * @return boolean
     */
    public function has($name)
    {
        return isset($this->services[$name]);
    }

    /**
     *
     * @param  string $name
     * @return object
     */
    public function get($name)
    {
        if (!$this->has($name)) {
            throw new FixtureException(sprintf('Service with the name "%s" dont exists', $name));
        }

        return $this->services[$name];
    }

    /**
     *
     * @param  string $name
     * @return ServiceProvider
     */
    public function remove($name)
    {
        if ($this->has($name)) {
            unset($this->services[$name]);
        }

        return $this;
    }
}
