<?php declare(strict_types=1);

namespace DavidBadura\Fixtures\Converter;

use DavidBadura\Fixtures\Exception\FixtureException;

/**
 * @author David Badura <d.badura@gmx.de>
 */
class ConverterRepository implements ConverterRepositoryInterface
{
    /**
     *
     * @var ConverterInterface[]
     */
    protected $converters = [];

    public function addConverter(ConverterInterface $converter): void
    {
        $name = $converter->getName();
        if ($this->hasConverter($name)) {
            throw new FixtureException(sprintf('Converter with the name "%s" already exists', $name));
        }

        $this->converters[$name] = $converter;
    }

    public function hasConverter(string $name): bool
    {
        return isset($this->converters[$name]);
    }

    public function getConverter(string $name): ?ConverterInterface
    {
        if (!$this->hasConverter($name)) {
            return null;
        }

        return $this->converters[$name];
    }

    public function removeConverter(string $name): void
    {
        if ($this->hasConverter($name)) {
            unset($this->converters[$name]);
        }
    }
}
