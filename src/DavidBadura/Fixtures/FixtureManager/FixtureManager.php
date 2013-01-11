<?php

namespace DavidBadura\Fixtures\FixtureManager;

use DavidBadura\Fixtures\Event\PreExecuteEvent;
use DavidBadura\Fixtures\Event\PostExecuteEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use DavidBadura\Fixtures\Executor\ExecutorInterface;
use DavidBadura\Fixtures\Loader\LoaderInterface;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class FixtureManager implements FixtureManagerInterface
{

    /**
     *
     * @var LoaderInterface
     */
    private $loader;

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
        ExecutorInterface $executor,
        EventDispatcherInterface $eventDispatcher)
    {
        $this->loader = $loader;
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
     * @return ExecutorInterface
     */
    public function getExecutor()
    {
        return $this->executor;
    }

    /**
     *
     * @return EventDispatcherInterface
     */
    public function getEventDispatcher()
    {
        return $this->eventDispatcher;
    }

    /**
     *
     * @param array $options
     */
    public function load($path, array $options = array())
    {
        $collection = $this->loader->load($path);

        $event = new PreExecuteEvent($collection, $options);
        $this->eventDispatcher->dispatch(FixtureEvents::onPreExecute, $event);

        $collection = $event->getCollection();
        $options = $event->getOptions();

        $this->executor->execute($collection);

        $event = new PostExecuteEvent($collection, $options);
        $this->eventDispatcher->dispatch(FixtureEvents::onPostExecute, $event);

        $collection = $event->getCollection();
        $options = $event->getOptions();

        return $collection;
    }

    /**
     *
     * @return FixtureManager
     */
    static public function createDefaultFixtureManager()
    {
        $yamlLoader = new Loader\YamlLoader();
        $arrayLoader = new Loader\ArrayLoader();
        $loader = new Loader\LoaderChain(array($yamlLoader, $arrayLoader));

        $executor = Executor\Executor::createDefaultExecutor();

        $tagFilterListener = new EventListener\TagFilterListener();

        $eventDispatcher = new \Symfony\Component\EventDispatcher\EventDispatcher();
        $eventDispatcher->addListener('davidbadura_fixtures.listener.tag_filter', $tagFilterListener);

        return new self($loader, $executor, $eventDispatcher);
    }

}