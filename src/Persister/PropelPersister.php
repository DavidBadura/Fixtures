<?php declare(strict_types=1);

namespace DavidBadura\Fixtures\Persister;

use DavidBadura\Fixtures\Fixture\FixtureData;

/**
 * @author David Badura <d.badura@gmx.de>
 */
class PropelPersister implements PersisterInterface
{
    protected $objects = [];

    public function persist(FixtureData $data): void
    {
        $this->objects[] = $data->getObject();
    }

    public function flush(): void
    {
        foreach ($this->objects as $object) {
            $object->save();
        }
    }
}
