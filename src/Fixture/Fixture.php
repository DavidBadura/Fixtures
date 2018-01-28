<?php declare(strict_types=1);

namespace DavidBadura\Fixtures\Fixture;

use DavidBadura\Fixtures\Exception\FixtureException;

/**
 * @author David Badura <d.badura@gmx.de>
 */
class Fixture implements \IteratorAggregate, \Countable
{
    protected static $defaultParameters = [];
    protected static $defaultConverter = 'default';

    private $name;
    private $converter;
    private $properties = [];
    private $fixtureData = [];

    public function __construct(string $name, string $converter = null, ParameterBag $properties = null)
    {
        $this->name = $name;
        $this->converter = $converter ?: self::$defaultConverter;

        $params = $properties ? $properties->toArray() : [];
        $this->properties = new ParameterBag(array_merge(self::$defaultParameters, $params));
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getConverter(): string
    {
        return $this->converter;
    }

    public function has(string $key): bool
    {
        return isset($this->fixtureData[$key]);
    }

    public function get(string $key): FixtureData
    {
        if (!$this->has($key)) {
            throw new FixtureException(sprintf(
                'Fixture data with key "%s" does not exist in "%s" fixture',
                $key,
                $this->name
            ));
        }

        return $this->fixtureData[$key];
    }

    public function add(FixtureData $fixtureData): void
    {
        $key = $fixtureData->getKey();
        if ($this->has($key)) {
            throw new FixtureException(sprintf(
                'fixture data with key "%s" already exists in "%s" fixture',
                $key,
                $this->name
            ));
        }

        $this->fixtureData[$key] = $fixtureData;
        $fixtureData->setFixture($this);
    }

    public function remove(FixtureData $fixtureData): void
    {
        $key = $fixtureData->getKey();
        if ($this->has($key)) {
            unset($this->fixtureData[$key]);
        }
    }

    public function getProperties(): ParameterBag
    {
        return $this->properties;
    }

    public function setProperties(ParameterBag $properties): void
    {
        $this->properties = $properties;
    }

    public function getIterator(): \Iterator
    {
        return new \ArrayIterator($this->fixtureData);
    }

    public function count(): int
    {
        return count($this->fixtureData);
    }

    public static function create(string $name, array $data): self
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

    public static function setDefaultParameters(array $params = []): void
    {
        self::$defaultParameters = $params;
    }

    public static function getDefaultParameters(): array
    {
        return self::$defaultParameters;
    }

    public static function setDefaultConverter(string $converter): void
    {
        self::$defaultConverter = $converter;
    }

    public static function getDefaultConverter(): string
    {
        return self::$defaultConverter;
    }
}
