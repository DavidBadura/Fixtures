<?php declare(strict_types=1);

namespace DavidBadura\Fixtures\Fixture;

use DavidBadura\Fixtures\Exception\FixtureException;

/**
 * @author David Badura <d.badura@gmx.de>
 */
class FixtureData
{
    protected $key;
    protected $data;
    protected $object;
    protected $fixture;
    private $loaded = false;

    public function __construct(string $key, $data)
    {
        $this->key = $key;
        $this->data = $data;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setData($data): void
    {
        $this->data = $data;
    }

    public function setObject($object): void
    {
        if ($this->object) {
            throw new FixtureException("fixture data has already an object");
        }

        $this->object = $object;
    }

    public function getObject()
    {
        return $this->object;
    }

    public function hasObject(): bool
    {
        return $this->object !== null;
    }

    public function getProperties(): array
    {
        return $this->fixture->getProperties();
    }

    public function setFixture(Fixture $fixture): void
    {
        if ($this->fixture) {
            throw new FixtureException("Fixture data has a parent already");
        }

        $this->fixture = $fixture;
    }

    public function getFixture(): Fixture
    {
        return $this->fixture;
    }

    public function setLoaded(bool $loaded = true): void
    {
        $this->loaded = $loaded;
    }

    public function isLoaded(): bool
    {
        return $this->loaded;
    }

    public function get(string $key)
    {
        if (!$this->has($key)) {
            return null;
        }

        return $this->data[$key];
    }

    public function has(string $key): bool
    {
        return isset($this->data[$key]);
    }

    public function set(string $key, $value): void
    {
        $this->data[$key] = $value;
    }

    public function remove(string $key): void
    {
        if ($this->has($key)) {
            unset($this->data[$key]);
        }
    }
}
