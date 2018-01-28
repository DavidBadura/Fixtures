<?php declare(strict_types=1);

namespace DavidBadura\Fixtures\Persister;

use DavidBadura\Fixtures\Exception\FixtureException;
use DavidBadura\Fixtures\Fixture\FixtureData;
use DavidBadura\Fixtures\Util\Matcher;

/**
 * @author David Badura <d.badura@gmx.de>
 */
class MatchPersister implements PersisterInterface
{
    private $mapping = [];

    public function add(PersisterInterface $persister, string $pattern)
    {
        $this->mapping[] = [
            'persister' => $persister,
            'pattern' => $pattern,
        ];

        return $this;
    }

    public function persist(FixtureData $data): void
    {
        $object = $data->getObject();
        $class = get_class($object);

        foreach ($this->mapping as $mapping) {
            if (!Matcher::match($class, $mapping['pattern'])) {
                continue;
            }

            $mapping['persister']->persist($data);
        }

        throw new FixtureException(sprintf('not matching for class "%s"', $class));
    }

    public function flush(): void
    {
        foreach ($this->mapping as $mapping) {
            $mapping['persister']->flush();
        }
    }
}
