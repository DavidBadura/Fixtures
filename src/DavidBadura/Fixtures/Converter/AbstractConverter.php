<?php

namespace DavidBadura\Fixtures\Converter;

use DavidBadura\Fixtures\Fixture\FixtureData;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
abstract class AbstractConverter implements ConverterInterface
{

    /**
     * @param object $object
     * @param FixtureData $fixtureData
     */
    public function finalizeObject($object, FixtureData $fixtureData)
    {
        // do nothing
    }
}
