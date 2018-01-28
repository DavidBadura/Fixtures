<?php declare(strict_types=1);

namespace DavidBadura\Fixtures\Executor;

use DavidBadura\Fixtures\Fixture\FixtureCollection;

/**
 * @author David Badura <d.badura@gmx.de>
 */
interface ExecutorInterface
{
    public function execute(FixtureCollection $fixtures): void;

    public function createObject(FixtureCollection $collection, string $name, string $key);

    public function finalizeObject(FixtureCollection $collection, string $name, string $key);
}
