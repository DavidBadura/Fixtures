<?php

namespace DavidBadura\Fixtures;

use DavidBadura\Fixtures\Event\PreExecuteEvent;
use DavidBadura\Fixtures\Event\PostExecuteEvent;
use DavidBadura\Fixtures\Event\PostFixtureLoadEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use DavidBadura\Fixtures\Executor\ExecutorInterface;
use DavidBadura\Fixtures\Logger\Logger;
use DavidBadura\Fixtures\Logger\NullLogger;
use DavidBadura\Fixtures\Loader\LoaderInterface;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class FixtureManager
{

    /**
     *
     * @var LoaderInterface
     */
    private $loader;

    /**
     *
     * @var FixtureFactory
     */
    private $fixtureFactory;

    /**
     *
     * @var ExecutorInterface
     */
    private $executor;

    /**
     *
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     *
     * @param PersisterInterface $persister
     */
    public function __construct(LoaderInterface $loader,
        FixtureFactory $fixtureFactory, ExecutorInterface $executor,
        EventDispatcherInterface $eventDispatcher)
    {
        $this->loader = $loader;
        $this->fixtureFactory = $fixtureFactory;
        $this->executor = $executor;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     *
     * @return LoaderInterface
     */
    public function getLoader()
    {
        return $this->loader;
    }

    /**
     *
     * @return FixtureFactory
     */
    public function getFixtureFactory()
    {
        return $this->fixtureFactory;
    }

    /**
     *
     * @return ExecutorInterface
     */
    public function getExecutor()
    {
        return $this->executor;
    }

    /**
     *
     * @param array $options
     */
    public function load(array $options = array(), Logger $logger = null)
    {
        if(!$logger) {
            $logger = new NullLogger();
        }

        $options = array_merge(array(
            'fixtures' => array()
        ), $options);

        $logger->headline('search fixture files...');

        $data = $this->loader->load($options['fixtures']);

        $event = new PostFixtureLoadEvent($data, $options);
        $this->eventDispatcher->dispatch(FixtureEvents::onPostFixtureLoad, $event);

        $logger->headline('load fixtures...');

        $data = $event->getData();
        $options = $event->getOptions();

        $fixtures = $this->fixtureFactory->createFixtures($data);

        $event = new PreExecuteEvent($fixtures, $options);
        $this->eventDispatcher->dispatch(FixtureEvents::onPreExecute, $event);

        $fixtures = $event->getFixtures();
        $options = $event->getOptions();

        foreach($fixtures as $fixture) {
            $logger->log($fixture->getName());
        }

        $this->executor->execute($fixtures);

        $event = new PostExecuteEvent($fixtures, $options);
        $this->eventDispatcher->dispatch(FixtureEvents::onPostExecute, $event);

        $fixtures = $event->getFixtures();
        $options = $event->getOptions();

        $logger->headline('done!');

        return $fixtures;
    }

}
