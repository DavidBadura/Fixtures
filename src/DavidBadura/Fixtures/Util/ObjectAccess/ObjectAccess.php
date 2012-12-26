<?php

namespace DavidBadura\Fixtures\Util\ObjectAccess;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class ObjectAccess
{

    /**
     *
     * @var object
     */
    protected $object;

    /**
     *
     * @var \ReflectionClass
     */
    protected $reflClass;

    /**
     *
     * @param object $object
     */
    public function __construct($object)
    {
        $this->object = $object;
        $this->reflClass = new \ReflectionClass($object);
    }

    /**
     *
     * @param string $property
     * @param mixed $value
     * @throws ObjectAccessException
     */
    public function writeProperty($property, $value)
    {
        $noPublic = array();

        $camelizeProperty = $this->camelize($property);

        $getter = 'get' . $camelizeProperty;
        $setter = 'set' . $camelizeProperty;
        $adder = 'add' . $camelizeProperty;

        /*
         * try with setter method (set*)
         */
        if ($this->reflClass->hasMethod($setter)) {

            if ($this->reflClass->getMethod($setter)->isPublic()) {
                $this->object->$setter($this->prepareValue($value, $setter));
                return;
            }

            $noPublic[] = sprintf('Method "%s()" is not public', $setter);
        }

        /*
         * try with adder method
         */
        if (is_array($value)) {

            if ($this->reflClass->hasMethod($adder)) {

                if ($this->reflClass->getMethod($adder)->isPublic()) {
                    foreach ($value as $val) {
                        $this->object->$adder($this->prepareValue($val, $adder));
                    }

                    return;
                }

                $noPublic[] = sprintf('Method "%s()" is not public', $adder);
            }

            /*
             * try non plural adder
             * remove plural "s"
             */
            if (substr($property, -1, 1) == 's') {
                $singularAdder = 'add' . $this->camelize(substr($property, 0, -1));

                if ($this->reflClass->hasMethod($singularAdder)) {

                    if ($this->reflClass->getMethod($singularAdder)->isPublic()) {
                        foreach ($value as $val) {
                            $this->object->$singularAdder($this->prepareValue($val, $singularAdder));
                        }

                        return;
                    }

                    $noPublic[] = sprintf('Method "%s()" is not public', $singularAdder);
                }
            }

            /*
             * needed to support ArrayCollection
             */
            if ($this->reflClass->hasMethod($getter) && $this->reflClass->getMethod($getter)->isPublic()) {
                $collection = $this->object->$getter();
                if ($collection instanceof \Doctrine\Common\Collections\ArrayCollection) {
                    foreach ($value as $val) {
                        $collection->add($val);
                    }

                    return;
                }
            }
        }

        /*
         * try property
         */
        if ($this->reflClass->hasProperty($property)) {
            if ($this->reflClass->getProperty($property)->isPublic()) {
                $this->object->$property = $value;

                return;
            }

            $noPublic[] = sprintf('Property "%s" is not public. Maybe you should create the method "%s()" or "%s()"?', $property, $setter, $adder);
        }

        /*
         * needed to support \stdClass instances
         */
        if ($this->object instanceof \stdClass) {
            $this->object->$property = $value;

            return;
        }

        /*
         * try with magic __set method
         */
        if ($this->reflClass->hasMethod('__set')) {
            $this->object->$property = $value;

            return;
        }

        if (count($noPublic) > 0) {
            throw new ObjectAccessException(sprintf('property "%s" is not writeable in class "%s"' . "\n"
                . implode("\n", $noPublic), $property, $this->reflClass->getName()));
        }

        throw new ObjectAccessException(sprintf('property "%s" is not writeable in class "%s"' . "\n"
            . 'Maybe you should create the method "%s()" or "%s()"?', $property, $this->reflClass->getName(), $setter, $adder));
    }

    /**
     *
     * @param string $property
     * @return string
     */
    protected function camelize($property)
    {
        return preg_replace_callback('/(^|_|\.)+(.)/', function ($match) {
                    return ('.' === $match[1] ? '_' : '') . strtoupper($match[2]);
                }, $property);
    }

    /**
     *
     * @param mixed $value
     * @param string $method
     * @param int $parameter
     * @return \DateTime|mixed
     * @throws \UnexpectedValueException
     */
    public function prepareValue($value, $method, $parameter = 0)
    {
        if (!is_numeric($value) && !is_string($value)) {
            return $value;
        }

        $params = $this->reflClass->getMethod($method)->getParameters();

        if (!$params[$parameter]->getClass()) {
            return $value;
        }

        $class = $params[$parameter]->getClass()->getName();

        if ($class == 'DateTime') {

            try {
                return new \DateTime($value);
            } catch (\Exception $e) {
                throw new ObjectAccessException('Could not convert '.$value.' to \DateTime for '.get_class($this->object).'::'.$method, 0, $e);
            }

        }

        return $value;
    }

}
