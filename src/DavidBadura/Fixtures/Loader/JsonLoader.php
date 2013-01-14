<?php

namespace DavidBadura\Fixtures\Loader;

use DavidBadura\Fixtures\Fixture\FixtureCollection;
use Symfony\Component\Finder\Finder;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class JsonLoader implements LoaderInterface
{

    /**
     *
     * @param  mixed     $path
     * @return FixtureCollection
     */
    public function load($path)
    {
        $finder = new Finder();
        $finder->in($path)->name('*.json');

        $fixtures = array();
        foreach ($finder->files() as $file) {
            $data = json_decode(file_get_contents($file->getPathname()), true);
            $fixtures = array_merge_recursive($fixtures, $data);
        }

        $collection = FixtureCollection::create($fixtures);
        return $collection;
    }

}