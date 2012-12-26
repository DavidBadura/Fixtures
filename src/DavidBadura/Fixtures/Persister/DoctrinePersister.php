<?php

namespace DavidBadura\Fixtures\Persister;

use Doctrine\Common\Persistence\ObjectManager;

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
     * @param object $object
     */
    public function addObject($object)
    {
        $this->om->persist($object);
    }

    /**
     *
     *
     */
    public function save()
    {
        $this->om->flush();
    }

}
