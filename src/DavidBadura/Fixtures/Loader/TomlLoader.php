<?php

namespace DavidBadura\Fixtures\Loader;

use DavidBadura\Fixtures\Fixture\FixtureCollection;
use Symfony\Component\Finder\Finder;
use Toml\Parser;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class TomlLoader implements LoaderInterface
{

    /**
     *
     * @param  mixed     $path
     * @return FixtureCollection
     */
    public function load($path)
    {
        $finder = new Finder();
        $finder->in($path)->name('*.toml');

        $fixtures = array();
        foreach ($finder->files() as $file) {
            $data = Parser::fromFile($file->getPathname());
            $fixtures = array_merge_recursive($fixtures, $data);
        }

        $collection = FixtureCollection::create($fixtures);
        return $collection;
    }

}