<?php declare(strict_types=1);

namespace DavidBadura\Fixtures\Loader;

use DavidBadura\Fixtures\Fixture\FixtureCollection;

/**
 * @author David Badura <d.badura@gmx.de>
 */
class JsonLoader implements LoaderInterface
{
    public function load($path, array $options = []): FixtureCollection
    {
        $data = json_decode(file_get_contents($path), true);

        return FixtureCollection::create($data);
    }
}
