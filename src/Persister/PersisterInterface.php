<?php declare(strict_types=1);

namespace DavidBadura\Fixtures\Persister;

use DavidBadura\Fixtures\Fixture\FixtureData;

/**
 * @author David Badura <d.badura@gmx.de>
 */
interface PersisterInterface
{
    public function persist(FixtureData $data): void;

    public function flush(): void;
}
