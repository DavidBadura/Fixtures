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
    private $converters = array();

    /**
     *
     * @var array
     */
    private $classes = array();


    /**
     *
     * @param  ConverterInterface                  $converter
     * @return \DavidBadura\Fixtures\FixtureManager
     * @throws \Exception
     */
    public function addConverter(ConverterInterface $converter)
    {
        $name = $converter->getName();
        if (isset($this->converters[$name])) {
            throw new FixtureException(sprintf('Converter with the name "%s" already exists', $name));
        }

        $this->converters[$name] = $converter;
        $this->classes[get_class($converter)] = true;

        return $this;
    }

    /**
     *
     * @param  string  $name
     * @return boolean
     */
    public function hasConverter($name)
    {
        return isset($this->converters[$name]);
    }

    /**
     *
     * @param  string                    $name
     * @return ConverterInterface
     * @throws \Exception
     */
    public function getConverter($name)
    {
        if (!isset($this->converters[$name])) {
            return null;
        }

        return $this->converters[$name];
    }

    /**
     *
     * @param  string                                     $name
     * @return \DavidBadura\Fixtures\FixtureManager
     * @throws \Exception
     */
    public function removeConverter($name)
    {
        if (isset($this->converters[$name])) {
            unset($this->converters[$name]);
            unset($this->classes[get_class($this->converters[$name])]);
        }

        return $this;
    }

}
