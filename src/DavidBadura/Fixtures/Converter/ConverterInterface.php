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
