<?php

namespace DavidBadura\Fixtures\Loader;

use DavidBadura\Fixtures\Fixture\FixtureCollection;
use Symfony\Component\Yaml\Yaml;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class YamlLoader implements LoaderInterface
{

    /**
     *
     * @param  string            $path
     * @return FixtureCollection
     */
    public function load($path, array $options = array())
    {
        $data = Yaml::parse($path);

        return FixtureCollection::create($data);
    }

}
