<?php declare(strict_types=1);

namespace DavidBadura\Fixtures\Loader;

use DavidBadura\Fixtures\Fixture\FixtureCollection;
use Symfony\Component\Yaml\Yaml;

/**
 * @author David Badura <d.badura@gmx.de>
 */
class YamlLoader implements LoaderInterface
{
    public function load($path, array $options = []): FixtureCollection
    {
        $data = Yaml::parse(file_get_contents($path));

        return FixtureCollection::create($data);
    }
}
