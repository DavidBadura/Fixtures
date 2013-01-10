<?php

namespace DavidBadura\Fixtures;

use DavidBadura\Fixtures\Converter\ConverterInterface;
use DavidBadura\Fixtures\Exception\FixtureException;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class Fixture implements \IteratorAggregate, \Countable
{

    /**
     *
     * @var string
     */
    private $name;

    /**
     *
     * @var string[]
     */
    private $tags = array();

    /**
     *
     * @var boolean
     */
    private $enableValidation = true;

    /**
     *
     * @var array|null
     */
    private $validationGroups = null;

    /**
     *
     * @var ConverterInterface
     */
    private $converter;

    /**
     *
     * @var array
     */
    private $properties = array();

    /**
     *
     * @var FixtureData[]
     */
    private $fixtureData = array();

    /**
     *
     * @param string                    $name
     * @param ConverterInterface $converter
     * @param type                      $persister
     * @param array                     $data
     */
    public function __construct($name, ConverterInterface $converter)
    {
        $this->name = $name;
        $this->converter = $converter;
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     *
     * @return string[]
     */
    public function getTags()
    {
        return array_keys($this->tags);
    }

    /**
     *
     * @param  type                                $tags
     * @return \DavidBadura\Fixtures\Fixture
     */
    public function addTags(array $tags)
    {
        foreach ($tags as $tag) {
            $this->addTag($tag);
        }

        return $this;
    }

    /**
     *
     * @param  string                              $tag
     * @return \DavidBadura\Fixtures\Fixture
     */
    public function addTag($tag)
    {
        $this->tags[$tag] = true;

        return $this;
    }

    /**
     *
     * @param  string  $tag
     * @return boolean
     */
    public function hasTag($tag)
    {
        return isset($this->tags[$tag]);
    }

    /**
     *
     * @param  string                              $tag
     * @return \DavidBadura\Fixtures\Fixture
     */
    public function removeTag($tag)
    {
        if (isset($this->tags[$tag])) {
            unset($this->tags[$tag]);
        }

        return $this;
    }

    /**
     *
     * @return boolean
     */
    public function isEnableValidation()
    {
        return $this->enableValidation;
    }

    /**
     *
     * @param  type                                $enableValidation
     * @return \DavidBadura\Fixtures\Fixture
     */
    public function setEnableValidation($enableValidation)
    {
        $this->enableValidation = $enableValidation;

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getValidationGroups()
    {
        return $this->validationGroups;
    }

    /**
     *
     * @param  string                              $validationGroup
     * @return \DavidBadura\Fixtures\Fixture
     */
    public function setValidationGroups($validationGroups)
    {
        if (is_null($validationGroups)) {
            $this->validationGroups = null;

            return $this;
        }

        if (!is_array($validationGroups)) {
            $validationGroups = array($validationGroups);
        }

        $this->validationGroups = $validationGroups;

        return $this;
    }

    /**
     *
     * @return ConverterInterface
     */
    public function getConverter()
    {
        return $this->converter;
    }

    /**
     *
     * @param  string  $key
     * @return boolean
     */
    public function hasFixtureData($key)
    {
        return isset($this->fixtureData[$key]);
    }

    /**
     *
     * @param  string           $key
     * @return FixtureData
     * @throws FixtureException
     */
    public function getFixtureData($key)
    {
        if (!$this->hasFixtureData($key)) {
            throw new FixtureException(sprintf('Fixture data with key "%s" does not exist in "%s" fixture', $key, $this->name));
        }

        return $this->fixtureData[$key];
    }

    /**
     *
     * @param  FixtureData                         $fixtureData
     * @return \DavidBadura\Fixtures\Fixture
     * @throws FixtureException
     */
    public function addFixtureData(FixtureData $fixtureData)
    {
        $key = $fixtureData->getKey();
        if ($this->hasFixtureData($key)) {
            throw new FixtureException(sprintf('fixture data with key "%s" already exists in "%s" fixture', $key, $this->name));
        }

        $this->fixtureData[$key] = $fixtureData;
        $fixtureData->setFixture($this);

        return $this;
    }

    /**
     *
     * @param  FixtureData                         $fixtureData
     * @return \DavidBadura\Fixtures\Fixture
     */
    public function removeFixtureData(FixtureData $fixtureData)
    {
        $key = $fixtureData->getKey();
        if ($this->hasFixtureData($key)) {
            unset($this->fixtureData[$key]);
        }

        return $this;
    }

    /**
     *
     * @return array
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     *
     * @param  array                               $properties
     * @return \DavidBadura\Fixtures\Fixture
     */
    public function setProperties(array $properties)
    {
        $this->properties =  $properties;

        return $this;
    }

    /**
     *
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->fixtureData);
    }

    /**
     *
     * @return int
     */
    public function count()
    {
        return count($this->fixtureData);
    }

}
