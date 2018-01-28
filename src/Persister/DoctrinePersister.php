<?php declare(strict_types=1);

namespace DavidBadura\Fixtures\Persister;

use DavidBadura\Fixtures\Fixture\FixtureData;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * @author David Badura <d.badura@gmx.de>
 */
class DoctrinePersister implements PersisterInterface
{
    private $om;

    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }

    public function persist(FixtureData $data): void
    {
        $object = $data->getObject();
        $this->om->persist($object);
    }

    public function flush(): void
    {
        $this->om->flush();
    }
}
