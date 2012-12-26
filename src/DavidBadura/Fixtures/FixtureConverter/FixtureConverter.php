<?php

namespace DavidBadura\Fixtures\FixtureConverter;

use DavidBadura\Fixtures\FixtureData;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
abstract class FixtureConverter implements FixtureConverterInterface
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
