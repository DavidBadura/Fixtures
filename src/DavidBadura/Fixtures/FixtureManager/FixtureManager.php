<?php

namespace DavidBadura\Fixtures\FixtureManager;

use DavidBadura\Fixtures\Event\PreExecuteEvent;
use DavidBadura\Fixtures\Event\PostExecuteEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use DavidBadura\Fixtures\Executor\ExecutorInterface;
use DavidBadura\Fixtures\Loader\LoaderInterface;
use DavidBadura\Fixtures\Persister\PersisterInterface;
use DavidBadura\Fixtures\FixtureEvents;

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
    static public function createDefaultFixtureManager($objectManager = null)
    {
        $yamlLoader = new \DavidBadura\Fixtures\Loader\YamlLoader();
        $arrayLoader = new \DavidBadura\Fixtures\Loader\ArrayLoader();
        $jsonLoader = new \DavidBadura\Fixtures\Loader\JsonLoader();
        $loader = new \DavidBadura\Fixtures\Loader\ChainLoader(array($yamlLoader, $arrayLoader, $jsonLoader));

        $executor = \DavidBadura\Fixtures\Executor\Executor::createDefaultExecutor();

        $tagFilterListener = new \DavidBadura\Fixtures\EventListener\TagFilterListener();

        $eventDispatcher = new \Symfony\Component\EventDispatcher\EventDispatcher();
        $eventDispatcher->addListener(FixtureEvents::onPreExecute ,$tagFilterListener);

        if($objectManager instanceof \Doctrine\ODM\MongoDB\DocumentManager) {
            $persister = new \DavidBadura\Fixtures\Persister\MongoDBPersister($objectManager);
        } elseif($objectManager instanceof \Doctrine\Common\Persistence\ObjectManager) {
            $persister = new \DavidBadura\Fixtures\Persister\DoctrinePersister($objectManager);
        }

        if($persister) {
            $persisterListener = new \DavidBadura\Fixtures\EventListener\PersistListener($persister);
            $eventDispatcher->addListener(FixtureEvents::onPostExecute, $persisterListener);
        }

        return new self($loader, $executor, $eventDispatcher);
    }

}