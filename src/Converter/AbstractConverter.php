<?php declare(strict_types=1);

namespace DavidBadura\Fixtures\Converter;

use DavidBadura\Fixtures\Fixture\FixtureData;

/**
 * @author David Badura <d.badura@gmx.de>
 */
abstract class AbstractConverter implements ConverterInterface
{
    public function finalizeObject($object, FixtureData $fixtureData): void
    {
    }
}
