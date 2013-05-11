<?php

namespace DavidBadura\Fixtures\Loader;

use DavidBadura\Fixtures\Fixture\FixtureCollection;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class PhpLoader implements LoaderInterface
{

    /**
     *
     * @param  mixed             $path
     * @return FixtureCollection
     */
    public function load($path, array $options = array())
    {
        $data = include $path;

        return FixtureCollection::create($data);
    }

}
