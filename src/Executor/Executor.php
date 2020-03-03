<?php declare(strict_types=1);

namespace DavidBadura\Fixtures\Executor;

use DavidBadura\Fixtures\Converter\ConverterInterface;
use DavidBadura\Fixtures\Converter\ConverterRepository;
use DavidBadura\Fixtures\Converter\ConverterRepositoryInterface;
use DavidBadura\Fixtures\Converter\DefaultConverter;
use DavidBadura\Fixtures\Exception\CircularReferenceException;
use DavidBadura\Fixtures\Exception\FixtureException;
use DavidBadura\Fixtures\Exception\ReferenceNotFoundException;
use DavidBadura\Fixtures\Fixture\FixtureCollection;

/**
 * @author David Badura <d.badura@gmx.de>
 */
class Executor implements ExecutorInterface
{
    private $stack = [];
    protected $converterRepository;

    public function __construct(ConverterRepositoryInterface $converterRepository)
    {
        $this->converterRepository = $converterRepository;
    }

    public function getConverterRepository(): ConverterRepositoryInterface
    {
        return $this->converterRepository;
    }

    public function addConverter(ConverterInterface $converter)
    {
        $this->converterRepository->addConverter($converter);
    }

    public function removeConverter(string $converter)
    {
        $this->converterRepository->removeConverter($converter);
    }

    public function execute(FixtureCollection $collection): void
    {
        $this->createObjects($collection);
        $this->finalizeObjects($collection);
    }

    private function createObjects(FixtureCollection $collection): void
    {
        $this->stack = [];

        foreach ($collection as $fixture) {
            foreach ($fixture as $data) {
                $this->createObject($collection, $fixture->getName(), $data->getKey());
            }
        }
    }

    private function finalizeObjects(FixtureCollection $collection): void
    {
        foreach ($collection as $fixture) {
            foreach ($fixture as $data) {
                $this->finalizeObject($collection, $fixture->getName(), $data->getKey());
            }
        }
    }

    public function createObject(FixtureCollection $collection, string $name, string $key)
    {
        $fixture = $collection->get($name);
        $fixtureData = $fixture->get($key);

        if ($fixtureData->hasObject() || $fixtureData->isLoaded()) {
            return null;
        }

        if (isset($this->stack[$name.':'.$key])) {
            throw new CircularReferenceException($name, $key, $this->stack);
        }

        $this->stack[$name.':'.$key] = true;

        $data = $fixtureData->getData();
        $preparedData = $this->prepareDataForCreate($data, $collection);
        $fixtureData->setData($preparedData);

        $converter = $this->converterRepository->getConverter($fixture->getConverter());
        $object = $converter->createObject($fixtureData);

        $fixtureData->setObject($object);

        unset($this->stack[$name.':'.$key]);

        return $object;
    }

    protected function prepareDataForCreate(array $data, FixtureCollection $collection)
    {
        $executor = $this;

        array_walk_recursive($data, function (&$value, $key) use ($executor, $collection) {
            if (is_string($value) && preg_match('/^@([-_\w]*):([-_\w]*)$/', $value, $hit)) {
                if (!$collection->has($hit[1]) || !$collection->get($hit[1])->get($hit[2])) {
                    throw new ReferenceNotFoundException($hit[1], $hit[2]);
                }

                $object = $collection->get($hit[1])->get($hit[2])->getObject();

                if (!$object) {
                    $executor->createObject($collection, $hit[1], $hit[2]);
                }

                $value = $collection->get($hit[1])->get($hit[2])->getObject();
            }
        });

        return $data;
    }

    public function finalizeObject(FixtureCollection $collection, string $name, string $key)
    {
        $fixture = $collection->get($name);
        $fixtureData = $fixture->get($key);

        if ($fixtureData->isLoaded()) {
            return $fixtureData->getObject();
        }

        $data = $fixtureData->getData();
        $preparedData = $this->prepareDataForFinalize($data, $collection);
        $fixtureData->setData($preparedData);

        $object = $fixtureData->getObject();

        $converter = $this->converterRepository->getConverter($fixture->getConverter());
        $converter->finalizeObject($object, $fixtureData);
        $fixtureData->setLoaded();

        return $object;
    }

    protected function prepareDataForFinalize(array $data, FixtureCollection $collection): array
    {
        array_walk_recursive($data, function (&$value, $key) use ($collection) {
            if (!is_string($value)) {
                return;
            }

            if (preg_match('/^@@([-_\w]*):([-_\w]*)$/', $value, $hit)) {
                if (!$collection->has($hit[1]) || !$collection->get($hit[1])->get($hit[2])) {
                    throw new ReferenceNotFoundException($hit[1], $hit[2]);
                }

                $object = $collection->get($hit[1])->get($hit[2])->getObject();

                if (!$object) {
                    throw new FixtureException(sprintf("Object for %s:%s does not exist", $hit[1], $hit[2]));
                }

                $value = $object;
            }
        });

        return $data;
    }

    public static function createDefaultExecutor(): self
    {
        $repository = new ConverterRepository();
        $repository->addConverter(new DefaultConverter());

        return new self($repository);
    }
}
