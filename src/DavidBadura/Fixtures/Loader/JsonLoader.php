<?php

namespace DavidBadura\Fixtures\Loader;

use DavidBadura\Fixtures\Fixture\FixtureCollection;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class JsonLoader implements LoaderInterface
{

    /**
     *
     * @param  string            $path
     * @return FixtureCollection
     */
    public function load($path, array $options = array())
    {
        $data = json_decode(file_get_contents($path), true);

        return FixtureCollection::create($data);
    }

}
