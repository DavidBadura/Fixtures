<?php

namespace DavidBadura\Fixtures\Loader;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
interface LoaderInterface
{

    /**
     *
     * @param mixed $path
     * @param array $options
     * @return FixtureCollection
     */
    public function load($path, array $options = array());
}