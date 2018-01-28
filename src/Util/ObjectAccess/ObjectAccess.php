<?php declare(strict_types=1);

namespace DavidBadura\Fixtures\Util\ObjectAccess;

/**
 * @author David Badura <d.badura@gmx.de>
 */
class ObjectAccess
{
    protected $object;
    protected $reflClass;

    public function __construct($object)
    {
        $this->object = $object;
        $this->reflClass = new \ReflectionClass($object);
    }

    public function writeProperty(string $property, $value): void
    {
        $noPublic = [];

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

            $noPublic[] = sprintf(
                'Property "%s" is not public. Maybe you should create the method "%s()" or "%s()"?',
                $property,
                $setter,
                $adder
            );
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
            throw new ObjectAccessException(
                sprintf(
                    "property \"%s\" is not writeable in class \"%s\"\n %s",
                    $property,
                    $this->reflClass->name,
                    implode("\n", $noPublic)
                )
            );
        }

        throw new ObjectAccessException(
            sprintf(
                "property \"%s\" is not writeable in class \"%s\"\n Maybe you should create the method \"%s()\" or \"%s()\"?",
                $property,
                $this->reflClass->name,
                $setter,
                $adder
            )
        );
    }

    public function readProperty(string $property)
    {
        $getter = 'get' . $this->camelize($property);
        $noPublic = [];

        /*
         * try with getter method (get*)
         */
        if ($this->reflClass->hasMethod($getter) && $this->reflClass->getMethod($getter)->isPublic()) {
            return $this->object->$getter();
        }

        /*
         * try property
         */
        if ($this->reflClass->hasProperty($property)) {
            if ($this->reflClass->getProperty($property)->isPublic()) {
                return $this->object->$property;
            }

            $noPublic[] = sprintf(
                'Property "%s" is not public. Maybe you should create the method "%s()"?',
                $property,
                $getter
            );
        }

        /*
         * needed to support \stdClass instances
         */
        if ($this->object instanceof \stdClass) {
            return $this->object->$property;
        }

        /*
         * try with magic __get method
         */
        if ($this->reflClass->hasMethod('__get')) {
            return $this->object->$property;
        }

        if (count($noPublic) > 0) {
            throw new ObjectAccessException(
                sprintf(
                    "property \"%s\" is not readable in class \"%s\"\n %s",
                    $property,
                    $this->reflClass->name,
                    implode("\n", $noPublic)
                )
            );
        }

        throw new ObjectAccessException(
            sprintf(
                "property \"%s\" is not readable in class \"%s\"\nMaybe you should create the method \"%s()\"?",
                $property,
                $this->reflClass->name,
                $getter
            )
        );
    }

    protected function camelize(string $property): string
    {
        return preg_replace_callback(
            '/(^|_|\.)+(.)/',
            function ($match) {
                return ('.' === $match[1] ? '_' : '') . strtoupper($match[2]);
            },
            $property
        );
    }

    public function prepareValue($value, string $method, int $parameter = 0)
    {
        if (!is_numeric($value) && !is_string($value)) {
            return $value;
        }

        $params = $this->reflClass->getMethod($method)->getParameters();

        if (!$params[$parameter]->getClass()) {
            return $value;
        }

        $class = $params[$parameter]->getClass()->getName();

        if ($class == \DateTime::class) {
            try {
                return new \DateTime($value);
            } catch (\Exception $e) {
                throw new ObjectAccessException(
                    sprintf(
                        'Could not convert %s to \DateTime for %s::%s',
                        $value,
                        get_class($this->object),
                        $method
                    ),
                    0,
                    $e
                );
            }
        }

        return $value;
    }
}
