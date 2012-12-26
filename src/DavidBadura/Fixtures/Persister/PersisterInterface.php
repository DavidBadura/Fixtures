<?php

namespace DavidBadura\Fixtures\Persister;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
interface PersisterInterface
{

    /**
     * @param object $object
     */
    public function addObject($object);

    /**
     *
     */
    public function save();

}
