<?php

namespace DavidBadura\Fixtures\Persister;

use DavidBadura\Fixtures\Fixture\FixtureData;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class PropelPersister implements PersisterInterface
{

    /**
     *
     * @var array
     */
    protected $objects = array();

    /**
     *
     * @param FixtureData $data
     */
    public function persist(FixtureData $data)
    {
        $this->objects[] = $data->getObject();
    }

    /**
     *
     *
     */
    public function flush()
    {
        foreach ($this->objects as $object) {
            $object->save();
        }
    }

}
