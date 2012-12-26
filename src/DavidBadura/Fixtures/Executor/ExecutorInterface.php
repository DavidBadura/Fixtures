<?php

namespace DavidBadura\Fixtures\Executor;

use DavidBadura\Fixtures\FixtureCollection;

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
}
