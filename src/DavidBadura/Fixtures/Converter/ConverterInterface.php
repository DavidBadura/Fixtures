<?php

namespace DavidBadura\Fixtures\Converter;

use DavidBadura\Fixtures\Fixture\FixtureData;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
interface ConverterInterface
{
    /**
     *
     * @return string
     */
    public function getName();

    /**
     *
     * @param FixtureData $fixtureData
     * @return object
     */
    public function createObject(FixtureData $fixtureData);

    /**
     *
     * @param object $object
     * @param FixtureData $fixtureData
     * @return
     */
    public function finalizeObject($object, FixtureData $fixtureData);
}
