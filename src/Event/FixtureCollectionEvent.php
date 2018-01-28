<?php declare(strict_types=1);

namespace DavidBadura\Fixtures\Event;

use DavidBadura\Fixtures\Fixture\FixtureCollection;
use DavidBadura\Fixtures\FixtureManager\FixtureManagerInterface;

/**
 * @author David Badura <d.badura@gmx.de>
 */
class FixtureCollectionEvent extends FixtureEvent
{
    private $collection;

    public function __construct(
        FixtureManagerInterface $fixtureManager,
        FixtureCollection $collection,
        array $options = []
    ) {
        parent::__construct($fixtureManager, $options);

        $this->collection = $collection;
    }

    public function getCollection(): FixtureCollection
    {
        return $this->collection;
    }

    public function setCollection(FixtureCollection $collection): void
    {
        $this->collection = $collection;
    }
}
