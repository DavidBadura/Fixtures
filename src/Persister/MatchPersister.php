<?php declare(strict_types=1);

namespace DavidBadura\Fixtures\Persister;

use DavidBadura\Fixtures\Exception\FixtureException;
use DavidBadura\Fixtures\Fixture\FixtureData;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class MatchPersister implements PersisterInterface
{
    /**
     *
     * @var array
     */
    private $mapping = [];

    /**
     *
     * @param  PersisterInterface $persister
     * @return self
     */
    public function add(PersisterInterface $persister, $pattern)
    {
        $this->mapping[] = [
            'persister' => $persister,
            'pattern' => $pattern,
        ];

        return $this;
    }

    /**
     *
     * @param FixtureData $data
     * @throws FixtureException
     */
    public function persist(FixtureData $data)
    {
        $object = $data->getObject();
        $class = get_class($object);

        foreach ($this->mapping as $mapping) {
            if (!Matcher::match($class, $mapping['pattern'])) {
                continue;
            }

            return $mapping['persister']->persist($data);
        }

        throw new FixtureException(sprintf('not matching for class "%s"', $class));
    }

    /**
     *
     *
     */
    public function flush()
    {
        foreach ($this->mapping as $mapping) {
            $mapping['persister']->flush();
        }
    }
}
