<?php

namespace DavidBadura\Fixtures\FixtureConverter;

use DavidBadura\Fixtures\FixtureData;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
interface FixtureConverterInterface
{

    /**
     *
     * @return string
     */
    public function getName();

    /**
     *
     * @return object
     */
    public function createObject(FixtureData $fixtureData);

    /**
     *
     * @param object $object
     * @param array  $data
     */
    public function finalizeObject($object, FixtureData $fixtureData);

}
