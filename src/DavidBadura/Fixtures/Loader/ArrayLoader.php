<?php

namespace DavidBadura\Fixtures\Loader;

use Symfony\Component\Finder\Finder;
use DavidBadura\Fixtures\FixtureCollection;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class ArrayLoader implements LoaderInterface
{

    /**
     *
     * @param  mixed     $path
     * @return FixtureCollection
     */
    public function load($path)
    {
        $finder = new Finder();
        $finder->in($path)->name('*.php');

        $fixtures = array();
        foreach ($finder->files() as $file) {
            $data = include $file->getPathname();
            $fixtures = array_merge_recursive($fixtures, $data);
        }

        $collection = FixtureCollection::create($fixtures);
        return $collection;
    }

}