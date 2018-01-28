<?php declare(strict_types=1);

namespace DavidBadura\Fixtures\Loader;

use DavidBadura\Fixtures\Fixture\FixtureCollection;
use Toml\Parser;

/**
 * @author David Badura <d.badura@gmx.de>
 */
class TomlLoader implements LoaderInterface
{
    public function load($path, array $options = []): FixtureCollection
    {
        $data = Parser::fromFile($path);

        return FixtureCollection::create($data);
    }
}
