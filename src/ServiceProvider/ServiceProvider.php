<?php declare(strict_types=1);

namespace DavidBadura\Fixtures\ServiceProvider;

use DavidBadura\Fixtures\Exception\FixtureException;

/**
 * @author David Badura <d.badura@gmx.de>
 */
class ServiceProvider implements ServiceProviderInterface
{
    protected $services = [];

    public function add(string $name, $service): void
    {
        if ($this->has($name)) {
            throw new FixtureException(sprintf('Service with the name "%s" already exists', $name));
        }

        $this->services[$name] = $service;
    }

    public function has(string $name): bool
    {
        return isset($this->services[$name]);
    }

    public function get(string $name)
    {
        if (!$this->has($name)) {
            throw new FixtureException(sprintf('Service with the name "%s" dont exists', $name));
        }

        return $this->services[$name];
    }

    public function remove(string $name): void
    {
        if ($this->has($name)) {
            unset($this->services[$name]);
        }
    }
}
