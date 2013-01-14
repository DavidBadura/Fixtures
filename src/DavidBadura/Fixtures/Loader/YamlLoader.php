<?php

namespace DavidBadura\Fixtures\Loader;

use DavidBadura\Fixtures\Fixture\FixtureCollection;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class YamlLoader implements LoaderInterface
{

    /**
     *
     * @param  mixed     $path
     * @return FixtureCollection
     */
    public function load($path)
    {
        $finder = new Finder();
        $finder->in($path)->name('*.yml');

        $fixtures = array();
        foreach ($finder->files() as $file) {
            $data = Yaml::parse($file->getPathname());
            $fixtures = array_merge_recursive($fixtures, $data);
        }

        $collection = FixtureCollection::create($fixtures);
        return $collection;
    }

}