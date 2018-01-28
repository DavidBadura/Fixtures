<?php declare(strict_types=1);

namespace DavidBadura\Fixtures\Persister;

use DavidBadura\Fixtures\Fixture\FixtureData;
use Doctrine\ODM\MongoDB\DocumentManager;

/**
 * @author David Badura <d.badura@gmx.de>
 * @author Florian Eckerstorfer <florian@theroadtojoy.at>
 */
class MongoDBPersister implements PersisterInterface
{
    protected $dm;

    public function __construct(DocumentManager $dm)
    {
        $this->dm = $dm;
    }

    public function persist(FixtureData $data): void
    {
        $object = $data->getObject();
        $this->dm->persist($object);
    }

    public function flush(): void
    {
        $this->dm->getSchemaManager()->ensureIndexes();
        $this->dm->flush(null, ['safe' => true]);
    }
}
