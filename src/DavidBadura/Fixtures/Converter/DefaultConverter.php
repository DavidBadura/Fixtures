<?php

namespace DavidBadura\Fixtures\Converter;

use DavidBadura\Fixtures\FixtureData;
use DavidBadura\Fixtures\Exception\ConverterException;
use DavidBadura\Fixtures\Util\ObjectAccess\ObjectAccess;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class DefaultConverter implements ConverterInterface
{

    public function createObject(FixtureData $fixtureData)
    {
        $properties = $fixtureData->getProperties();

        if (!isset($properties['class'])) {
            throw new ConverterException('Missing fixture "class" property');
        }

        $class = $properties['class'];
        $data = $fixtureData->getData();

        $constructor = (isset($properties['constructor'])) ? $properties['constructor'] : array();

        $object = null;
        if (empty($constructor)) {
            $object = new $class();
        } else {
            $reflection = new \ReflectionClass($class);

            if($reflection->hasMethod('__construct')) {
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

                        if(isset($constParams[$key])
                            && $constParams[$key]->getClass()
                            && $constParams[$key]->getClass()->getName() == 'DateTime') {
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

    public function finalizeObject($object, FixtureData $fixtureData)
    {
        $properties = $fixtureData->getProperties();
        $data = $fixtureData->getData();

        $constructor = (isset($properties['constructor'])) ? $properties['constructor'] : array();
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

    public function getName()
    {
        return 'default';
    }

}
