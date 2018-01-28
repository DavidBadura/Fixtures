<?php declare(strict_types=1);

namespace DavidBadura\Fixtures\Persister;

use Doctrine\ODM\MongoDB\DocumentManager;
use DavidBadura\Fixtures\Fixture\FixtureData;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 * @author Florian Eckerstorfer <florian@theroadtojoy.at>
 */
class MongoDBPersister implements PersisterInterface
{
    /**
     *
     * @var ObjectManager
     */
    protected $dm;

    /**
     *
     * @param DocumentManager $dm
     */
    public function __construct(DocumentManager $dm)
    {
        $this->dm = $dm;
    }

    /**
     *
     * @param FixtureData $data
     */
    public function persist(FixtureData $data)
    {
        $object = $data->getObject();
        $this->dm->persist($object);
    }

    /**
     *
     *
     */
    public function flush()
    {
        $this->dm->getSchemaManager()->ensureIndexes();
        $this->dm->flush(null, ['safe' => true]);
    }
}
