<?php declare(strict_types=1);

namespace DavidBadura\Fixtures\Loader;

use DavidBadura\Fixtures\Exception\FixtureException;
use DavidBadura\Fixtures\Fixture\FixtureCollection;
use DavidBadura\Fixtures\Exception\RuntimeException;
use DavidBadura\Fixtures\Util\Matcher;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class MatchLoader implements LoaderInterface
{
    /**
     *
     * @var array
     */
    private $mapping = [];

    /**
     *
     * @param  LoaderInterface $loader
     * @return self
     */
    public function add(LoaderInterface $loader, $pattern)
    {
        $this->mapping[] = [
            'loader'  => $loader,
            'pattern' => $pattern,
        ];

        return $this;
    }

    /**
     *
     * @param  string|array $path
     * @param array $options
     * @return FixtureCollection
     */
    public function load($path, array $options = [])
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
