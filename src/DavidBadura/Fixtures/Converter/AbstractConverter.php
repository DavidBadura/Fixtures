<?php

namespace DavidBadura\Fixtures\Converter;

use DavidBadura\Fixtures\FixtureData;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
abstract class AbstractConverter implements ConverterInterface
{

    /**
     *
     * @param object $object
     * @param array  $data
     */
    public function finalizeObject($object, FixtureData $fixtureData)
    {

    }

}
