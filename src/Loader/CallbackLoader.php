<?php declare(strict_types=1);

namespace DavidBadura\Fixtures\Loader;

use DavidBadura\Fixtures\Exception\FixtureException;
use DavidBadura\Fixtures\Fixture\FixtureCollection;

/**
 * @author David Badura <d.badura@gmx.de>
 */
class CallbackLoader implements LoaderInterface
{
    protected $callback;

    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    public function load($path, array $options = []): FixtureCollection
    {
        $callback = $this->callback;

        $collection = $callback($path, $options);

        if (is_array($collection)) {
            return FixtureCollection::create($collection);
        } elseif ($collection instanceof FixtureCollection) {
            return $collection;
        }

        throw new FixtureException('the callback function must return a FixtureCollection instance or a fixture array');
    }
}
