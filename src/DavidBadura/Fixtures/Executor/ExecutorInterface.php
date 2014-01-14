<?php

namespace DavidBadura\Fixtures\Executor;

use DavidBadura\Fixtures\Fixture\FixtureCollection;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
interface ExecutorInterface
{

    /**
     *
     * @param FixtureCollection $fixtures
     */
    public function execute(FixtureCollection $fixtures);

    /**
     *
     * @param FixtureCollection $collection
     * @param string $name
     * @param string $key
     * @return object
     */
    public function createObject(FixtureCollection $collection, $name, $key);

    /**
     *
     * @param FixtureCollection $collection
     * @param string $name
     * @param string $key
     * @return object
     */
    public function finalizeObject(FixtureCollection $collection, $name, $key);
}
