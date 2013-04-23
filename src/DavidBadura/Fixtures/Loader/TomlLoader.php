<?php

namespace DavidBadura\Fixtures\Loader;

use DavidBadura\Fixtures\Fixture\FixtureCollection;
use Toml\Parser;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class TomlLoader implements LoaderInterface
{

    /**
     *
     * @param  string     $path
     * @return FixtureCollection
     */
    public function load($path, array $options = array())
    {
        $data = Parser::fromFile($path);

        return FixtureCollection::create($data);
    }

}