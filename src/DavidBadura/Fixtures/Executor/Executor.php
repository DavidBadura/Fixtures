<?php

namespace DavidBadura\Fixtures\Executor;

use DavidBadura\Fixtures\FixtureCollection;
use DavidBadura\Fixtures\Exception\CircularReferenceException;
use DavidBadura\Fixtures\Exception\FixtureException;
use DavidBadura\Fixtures\Exception\ReferenceNotFoundException;

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
     * @var type
     */
    private $order = 0;

    /**
     *
     * @param FixtureCollection $fixtures
     */
    public function execute(FixtureCollection $fixtures)
    {
        $this->createObjects($fixtures);

        return $this->finalizeObjects($fixtures);
    }

    /**
     *
     * @param FixtureCollection $fixtures
     */
    private function createObjects(FixtureCollection $fixtures)
    {
        $this->stack = array();

        foreach ($fixtures as $fixture) {
            foreach ($fixture as $data) {

                if ($data->hasObject() || $data->isLoaded()) {
                    continue;
                }

                $this->createObject($fixtures, $fixture->getName(), $data->getKey());
            }
        }
    }

    /**
     *
     * @param FixtureCollection $fixtures
     */
    private function finalizeObjects(FixtureCollection $fixtures)
    {
        foreach ($fixtures as $fixture) {
            foreach ($fixture as $data) {

                if ($data->isLoaded()) {
                    continue;
                }

                $this->finalizeObject($fixtures, $fixture->getName(), $data->getKey());
            }
        }
    }

    /**
     *
     * @param  FixtureCollection $fixtures
     * @param  string            $name
     * @param  string            $key
     * @throws \Exception
     */
    public function createObject(FixtureCollection $fixtures, $name, $key)
    {

        if (isset($this->stack[$name . ':' . $key])) {
            throw new CircularReferenceException($name, $key, $this->stack);
        }

        $this->stack[$name . ':' . $key] = true;

        $fixture = $fixtures->get($name);
        $fixtureData = $fixture->getFixtureData($key);

        $executor = $this;
        $data = $fixtureData->getData();
        array_walk_recursive($data, function(&$value, $key) use ($executor, $fixtures) {
                if (preg_match('/^@([\w-_]*):([\w-_]*)$/', $value, $hit)) {

                    if (!$fixtures->has($hit[1]) || !$fixtures->get($hit[1])->getFixtureData($hit[2])) {
                        throw new ReferenceNotFoundException($hit[1], $hit[2]);
                    }

                    $object = $fixtures->get($hit[1])->getFixtureData($hit[2])->getObject();

                    if (!$object) {
                        $executor->createObject($fixtures, $hit[1], $hit[2]);
                    }

                    $value = $fixtures->get($hit[1])->getFixtureData($hit[2])->getObject();
                }
            });

        $fixtureData->setData($data);
        $object = $fixture->getConverter()->createObject($fixtureData);

        $fixtureData->setObject($object);
        $fixtureData->setOrder(++$this->order);

        unset($this->stack[$name . ':' . $key]);
    }

    /**
     *
     * @param  FixtureCollection $fixtures
     * @param  type              $name
     * @param  type              $key
     * @throws \Exception
     */
    public function finalizeObject(FixtureCollection $fixtures, $name, $key)
    {

        $fixture = $fixtures->get($name);
        $fixtureData = $fixture->getFixtureData($key);

        $executor = $this;
        $data = $fixtureData->getData();

        array_walk_recursive($data, function(&$value, $key) use ($executor, $fixtures) {

                if (!is_string($value)) {
                    return;
                }

                if (preg_match('/^@@([\w-_]*):([\w-_]*)$/', $value, $hit)) {

                    if (!$fixtures->has($hit[1]) || !$fixtures->get($hit[1])->getFixtureData($hit[2])) {
                        throw new ReferenceNotFoundException($hit[1], $hit[2]);
                    }

                    $object = $fixtures->get($hit[1])->getFixtureData($hit[2])->getObject();

                    if (!$object) {
                        throw new FixtureException(sprintf("Object for %s:%s does not exist", $hit[1], $hit[2]));
                    }

                    $value = $object;
                }
            });

        $fixtureData->setData($data);
        $object = $fixtureData->getObject();

        $fixture->getConverter()->finalizeObject($object, $fixtureData);
        $fixtureData->setLoaded();
    }

}
