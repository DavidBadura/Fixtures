<?php

namespace DavidBadura\Fixtures\Persister;

use DavidBadura\Fixtures\Fixture\FixtureData;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
interface PersisterInterface
{

    /**
     * @param object $object
     */
    public function persist(FixtureData $data);

    /**
     *
     */
    public function flush();

}
