<?php

namespace DavidBadura\Fixtures\Persister;

use Doctrine\ODM\MongoDB\DocumentManager;

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
     * @param object $object
     */
    public function addObject($object)
    {
        $this->dm->persist($object);
    }

    /**
     *
     *
     */
    public function save()
    {
        $this->dm->getSchemaManager()->ensureIndexes();
        $this->dm->flush(null, array('safe' => true));
    }

}
