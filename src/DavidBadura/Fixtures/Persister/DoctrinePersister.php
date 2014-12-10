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

        $metadata = $this->om->getClassMetadata(get_class($object));
        $identifier = $metadata->getIdentifier();

        if ($metadata->usesIdGenerator()
            && count(array_intersect($identifier, array_keys($data->getData()))) > 0
        ) {
            $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
            $metadata->setIdGenerator(new \Doctrine\ORM\Id\AssignedGenerator());
        }

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
