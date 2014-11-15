<?php

namespace DavidBadura\Fixtures\Converter;

use DavidBadura\Fixtures\Fixture\FixtureData;
use DavidBadura\Fixtures\Exception\ConverterException;
use DavidBadura\Fixtures\Util\ObjectAccess\ObjectAccess;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class DefaultConverter implements ConverterInterface
{

    /**
     * @param FixtureData $fixtureData
     * @return object
     */
    public function createObject(FixtureData $fixtureData)
    {
        $properties = $fixtureData->getProperties();
        $class = $properties->get('class');
        $constructor = $properties->get('constructor', array());
        $data = $fixtureData->getData();

        if (!$class) {
            throw new ConverterException('Missing fixture "class" property');
        }

        $object = null;
        if (empty($constructor)) {
            $object = new $class();
        } else {
            $reflection = new \ReflectionClass($class);

            if ($reflection->hasMethod('__construct')) {
                $constParams = $reflection->getMethod('__construct')->getParameters();
            } else {
                $constParams = array();
            }

            $args = array();
            foreach ($constructor as $key => $arg) {

                $optional = (substr($arg, 0, 1) == '?');
                $arg = ($optional) ? substr($arg, 1) : $arg;

                if (!isset($data[$arg]) && !$optional) {
                    throw new ConverterException(sprintf('Missing "%s" attribute', $arg));
                } elseif (isset($data[$arg])) {

                    $value = $data[$arg];

                    if (is_string($value)) {
                        $value = str_replace('{unique_id}', uniqid(), $value);

                        if (isset($constParams[$key])
                            && $constParams[$key]->getClass()
                            && $constParams[$key]->getClass()->getName() == 'DateTime'
                        ) {
                            $value = new \DateTime($value);
                        }
                    }

                    $args[] = $value;
                }
            }

            $object = $reflection->newInstanceArgs($args);
        }

        return $object;
    }

    /**
     * @param object $object
     * @param FixtureData $fixtureData
     * @throws \DavidBadura\Fixtures\Util\ObjectAccess\ObjectAccessException
     */
    public function finalizeObject($object, FixtureData $fixtureData)
    {
        $properties = $fixtureData->getProperties();
        $data = $fixtureData->getData();

        $constructor = $properties->get('constructor', array());
        $args = array();

        if (!empty($constructor)) {
            foreach ($constructor as $key) {
                $key = (substr($key, 0, 1) == '?') ? substr($key, 1) : $key;
                $args[$key] = true;
            }
        }

        $objectAccess = new ObjectAccess($object);

        foreach ($data as $property => $value) {
            if (!isset($args[$property])) {
                if (is_string($value)) {
                    $value = str_replace('{unique_id}', uniqid(), $value);
                }
                $objectAccess->writeProperty($property, $value);
            }
        }
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'default';
    }
}
