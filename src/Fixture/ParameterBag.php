<?php declare(strict_types=1);

namespace DavidBadura\Fixtures\Fixture;

/**
 * @author David Badura <d.badura@gmx.de>
 */
class ParameterBag implements \ArrayAccess
{
    protected $parameters;

    public function __construct(array $parameters = [])
    {
        $this->parameters = $parameters;
    }

    public function has(string $key): bool
    {
        return isset($this->parameters[$key]);
    }

    public function get(string $key, $default = null)
    {
        if (!$this->has($key)) {
            return $default;
        }

        return $this->parameters[$key];
    }

    public function set(string $key, $value): void
    {
        $this->parameters[$key] = $value;
    }

    public function remove(string $key): void
    {
        if ($this->has($key)) {
            unset($this->parameters[$key]);
        }
    }

    public function toArray(): array
    {
        return $this->parameters;
    }

    public function offsetExists($offset): bool
    {
        return $this->has((string)$offset);
    }

    public function offsetGet($offset)
    {
        return $this->get((string)$offset);
    }

    public function offsetSet($offset, $value): void
    {
        $this->set((string)$offset, $value);
    }

    public function offsetUnset($offset): void
    {
        $this->remove((string)$offset);
    }
}
