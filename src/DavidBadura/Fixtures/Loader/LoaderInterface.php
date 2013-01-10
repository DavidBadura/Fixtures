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
     * @param  mixed     $path
     * @return Fixture[]
     */
    public function load($path);
}