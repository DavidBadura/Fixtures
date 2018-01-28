<?php declare(strict_types=1);

namespace DavidBadura\Fixtures\Converter;

use DavidBadura\Fixtures\Fixture\FixtureData;

/**
 * @author David Badura <d.badura@gmx.de>
 */
interface ConverterInterface
{
    public function getName(): string;

    public function createObject(FixtureData $fixtureData);

    public function finalizeObject($object, FixtureData $fixtureData): void;
}
