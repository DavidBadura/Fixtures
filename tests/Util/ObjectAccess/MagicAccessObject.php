<?php

namespace DavidBadura\Fixtures\Util\ObjectAccess;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class MagicAccessObject
{

    protected $array = array();

    public function __set($property, $value)
    {
        $this->array[$property] = $value;
    }

    public function __get($property)
    {
        return $this->array[$property];
    }
}
