<?php declare(strict_types=1);

namespace DavidBadura\Fixtures\Fixture;

use DavidBadura\Fixtures\Exception\FixtureException;

/**
 * @author David Badura <d.badura@gmx.de>
 */
class FixtureCollection implements \IteratorAggregate, \Countable
{
    /**
     * @var Fixture[]
     */
    protected $fixtures = [];

    public function __construct(array $fixtures = [])
    {
        foreach ($fixtures as $fixture) {
            $this->add($fixture);
        }
    }

    public function add(Fixture $fixture): void
    {
        $name = $fixture->getName();

        if (isset($this->fixtures[$name])) {
            throw new FixtureException(sprintf('fixture with the name "%s" already exists', $name));
        }

        $this->fixtures[$name] = $fixture;
    }

    public function get(string $name): ?Fixture
    {
        if (!isset($this->fixtures[$name])) {
            return null;
        }

        return $this->fixtures[$name];
    }

    public function has(string $name): bool
    {
        return isset($this->fixtures[$name]);
    }

    public function remove(string $name): void
    {
        if (isset($this->fixtures[$name])) {
            unset($this->fixtures[$name]);
        }
    }

    /**
     * @return \ArrayIterator|Fixture[]
     */
    public function getIterator(): \Iterator
    {
        return new \ArrayIterator($this->fixtures);
    }

    public function count(): int
    {
        return count($this->fixtures);
    }

    public function merge(FixtureCollection $collection)
    {
        foreach ($collection as $fixture) {
            $this->add($fixture);
        }
    }

    public static function create(array $data): self
    {
        $collection = new self();

        foreach ($data as $name => $info) {
            $collection->add(Fixture::create($name, $info));
        }

        return $collection;
    }
}
