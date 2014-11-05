<?php

namespace DavidBadura\Fixtures\Executor;

use DavidBadura\Fixtures\Converter\ConverterRepositoryInterface;
use DavidBadura\Fixtures\Fixture\FixtureCollection;
use DavidBadura\Fixtures\Exception\CircularReferenceException;
use DavidBadura\Fixtures\Exception\FixtureException;
use DavidBadura\Fixtures\Exception\ReferenceNotFoundException;
use DavidBadura\Fixtures\Converter\ConverterInterface;

/**
 * @author David Badura <d.badura@gmx.de>
 */
class Executor implements ExecutorInterface
{

    /**
     *
     * @var array
     */
    private $stack = array();

    /**
     *
     * @var ConverterRepositoryInterface
     */
    protected $converterRepository;

    /**
     *
     * @param ConverterRepositoryInterface $converterRepository
     */
    public function __construct(ConverterRepositoryInterface $converterRepository)
    {
        $this->converterRepository = $converterRepository;
    }

    /**
     *
     * @return ConverterRepositoryInterface
     */
    public function getConverterRepository()
    {
        return $this->converterRepository;
    }

    /**
     *
     * @param ConverterInterface $converter
     */
    public function addConverter(ConverterInterface $converter)
    {
        $this->converterRepository->addConverter($converter);
    }

    /**
     *
     * @param string $converter
     */
    public function removeConverter($converter)
    {
        $this->converterRepository->removeConverter($converter);
    }

    /**
     *
     * @param FixtureCollection $collection
     */
    public function execute(FixtureCollection $collection)
    {
        $this->createObjects($collection);
        $this->finalizeObjects($collection);
    }

    /**
     *
     * @param FixtureCollection $collection
     */
    private function createObjects(FixtureCollection $collection)
    {
        $this->stack = array();

        foreach ($collection as $fixture) {
            foreach ($fixture as $data) {
                $this->createObject($collection, $fixture->getName(), $data->getKey());
            }
        }
    }

    /**
     *
     * @param FixtureCollection $collection
     */
    private function finalizeObjects(FixtureCollection $collection)
    {
        foreach ($collection as $fixture) {
            foreach ($fixture as $data) {
                $this->finalizeObject($collection, $fixture->getName(), $data->getKey());
            }
        }
    }

    /**
     *
     * @param  FixtureCollection $collection
     * @param  string            $name
     * @param  string            $key
     * @return object
     * @throws \Exception
     */
    public function createObject(FixtureCollection $collection, $name, $key)
    {
        $fixture = $collection->get($name);
        $fixtureData = $fixture->get($key);

        if ($fixtureData->hasObject() || $fixtureData->isLoaded()) {
            return;
        }

        if (isset($this->stack[$name . ':' . $key])) {
            throw new CircularReferenceException($name, $key, $this->stack);
        }

        $this->stack[$name . ':' . $key] = true;

        $data = $fixtureData->getData();
        $preparedData = $this->prepareDataForCreate($data, $collection);
        $fixtureData->setData($preparedData);
        
        $converter = $this->converterRepository->getConverter($fixture->getConverter());
        $object = $converter->createObject($fixtureData);

        $fixtureData->setObject($object);

        unset($this->stack[$name . ':' . $key]);

        return $object;
    }

    /**
     * @param array $data
     * @param FixtureCollection $collection
     * @return array
     */
    protected function prepareDataForCreate($data, FixtureCollection $collection)
    {
        $executor = $this;

        array_walk_recursive($data, function(&$value, $key) use ($executor, $collection) {
            if (is_string($value) && preg_match('/^@([\w-_]*):([\w-_]*)$/', $value, $hit)) {

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

    /**
     *
     * @param  FixtureCollection $collection
     * @param  type              $name
     * @param  type              $key
     * @return object
     * @throws \Exception
     */
    public function finalizeObject(FixtureCollection $collection, $name, $key)
    {
        $fixture = $collection->get($name);
        $fixtureData = $fixture->get($key);

        if ($fixtureData->isLoaded()) {
            return;
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

    /**
     * @param array $data
     * @param FixtureCollection $collection
     * @return array
     */
    protected function prepareDataForFinalize($data, FixtureCollection $collection)
    {
        array_walk_recursive($data, function(&$value, $key) use ($collection) {

            if (!is_string($value)) {
                return;
            }

            if (preg_match('/^@@([\w-_]*):([\w-_]*)$/', $value, $hit)) {

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

    /**
     *
     * @return Executor
     */
    public static function createDefaultExecutor()
    {
        $repository = new \DavidBadura\Fixtures\Converter\ConverterRepository();
        $repository->addConverter(new \DavidBadura\Fixtures\Converter\DefaultConverter());

        return new self($repository);
    }

}
