<?php

namespace DavidBadura\Fixtures\Converter;

use DavidBadura\Fixtures\Converter\ConverterInterface;
use DavidBadura\Fixtures\Exception\FixtureException;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class ConverterRepository implements ConverterRepositoryInterface
{
    /**
     *
     * @var ConverterInterface[]
     */
    protected $converters = array();

    /**
     *
     * @param  ConverterInterface $converter
     * @return $this
     * @throws \Exception
     */
    public function addConverter(ConverterInterface $converter)
    {
        $name = $converter->getName();
        if ($this->hasConverter($name)) {
            throw new FixtureException(sprintf('Converter with the name "%s" already exists', $name));
        }

        $this->converters[$name] = $converter;

        return $this;
    }

    /**
     *
     * @param  string $name
     * @return boolean
     */
    public function hasConverter($name)
    {
        return isset($this->converters[$name]);
    }

    /**
     *
     * @param  string $name
     * @return ConverterInterface
     * @throws \Exception
     */
    public function getConverter($name)
    {
        if (!$this->hasConverter($name)) {
            return null;
        }

        return $this->converters[$name];
    }

    /**
     *
     * @param  string $name
     * @return \DavidBadura\Fixtures\FixtureManager
     * @throws \Exception
     */
    public function removeConverter($name)
    {
        if ($this->hasConverter($name)) {
            unset($this->converters[$name]);
        }

        return $this;
    }

}
