<?php declare(strict_types=1);

namespace DavidBadura\Fixtures\Loader;

use DavidBadura\Fixtures\Exception\FixtureException;
use DavidBadura\Fixtures\Fixture\FixtureCollection;
use DavidBadura\Fixtures\Util\Matcher;

/**
 * @author David Badura <d.badura@gmx.de>
 */
class MatchLoader implements LoaderInterface
{
    private $mapping = [];

    public function add(LoaderInterface $loader, $pattern): self
    {
        $this->mapping[] = [
            'loader' => $loader,
            'pattern' => $pattern,
        ];

        return $this;
    }

    public function load($path, array $options = []): FixtureCollection
    {
        foreach ($this->mapping as $mapping) {
            if (!Matcher::match($path, $mapping['pattern'])) {
                continue;
            }

            return $mapping['loader']->load($path, $options);
        }

        throw new FixtureException(sprintf('not matching for "%s"', $path));
    }
}
