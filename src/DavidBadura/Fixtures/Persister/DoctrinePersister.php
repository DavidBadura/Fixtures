<?php

namespace DavidBadura\Fixtures\Persister;

use Doctrine\Common\Persistence\ObjectManager;
use DavidBadura\Fixtures\Fixture\FixtureData;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class DoctrinePersister implements PersisterInterface
{

    /**
     *
     * @var ObjectManager
     */
    protected $om;

    /**
     *
     * @param ObjectManager $om
     */
    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }

    /**
     *
     * @param FixtureData $data
     */
    public function persist(FixtureData $data)
    {
        $object = $data->getObject();
        $this->om->persist($object);
    }

    /**
     *
     *
     */
    public function flush()
    {
        $this->om->flush();
    }

}
