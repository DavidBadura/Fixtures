<?php

namespace DavidBadura\Fixtures\Exception;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class CircularReferenceException extends RuntimeException
{

    /**
     *
     * @var string
     */
    private $path;

    /**
     *
     * @param string $name
     * @param string $key
     * @param array  $path
     */
    public function __construct($name, $key, array $path)
    {
        parent::__construct($name, $key, sprintf('Circular reference detected for fixture "%s:%s", path: "%s -> %s:%s".', $name, $key, implode(' -> ', array_keys($path)), $name, $key));

        $this->path = $path;
    }

    /**
     *
     * @return array
     */
    public function getPath()
    {
        return $this->path;
    }

}
