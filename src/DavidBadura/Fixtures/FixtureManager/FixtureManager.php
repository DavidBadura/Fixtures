<?php

namespace DavidBadura\Fixtures\FixtureManager;

use DavidBadura\Fixtures\Fixture\FixtureCollection;
use DavidBadura\Fixtures\Event\FixtureEvent;
use DavidBadura\Fixtures\Event\FixtureCollectionEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use DavidBadura\Fixtures\Executor\ExecutorInterface;
use DavidBadura\Fixtures\Loader\LoaderInterface;
use DavidBadura\Fixtures\Persister\PersisterInterface;
use DavidBadura\Fixtures\FixtureEvents;
use DavidBadura\Fixtures\Exception\RuntimeException;

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
     * @var PersisterInterface
     */
    private $persister;

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
        PersisterInterface $persister,
        EventDispatcherInterface $eventDispatcher)
    {
        $this->loader = $loader;
        $this->executor = $executor;
        $this->persister = $persister;
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
     * @return PersisterInterface
     */
    public function getPersister()
    {
        return $this->persister;
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
     * @param string $path
     * @param array $options
     */
    public function load($path, array $options = array())
    {
        $event = new FixtureEvent($this, $options);
        $this->eventDispatcher->dispatch(FixtureEvents::onPreLoad, $event);
        $options = $event->getOptions();

        $collection = $this->loader->load($path);

        $event = new FixtureCollectionEvent($this, $collection, $options);
        $this->eventDispatcher->dispatch(FixtureEvents::onPreExecute, $event);
        $collection = $event->getCollection();
        $options    = $event->getOptions();

        if(isset($options['tags'])) {
            $this->filterByTags($collection, $options['tags']);
        }

        $event = new FixtureCollectionEvent($this, $collection, $options);
        $this->eventDispatcher->dispatch(FixtureEvents::onPreExecute, $event);
        $collection = $event->getCollection();
        $options    = $event->getOptions();

        $this->executor->execute($collection);

        $event = new FixtureCollectionEvent($this, $collection, $options);
        $this->eventDispatcher->dispatch(FixtureEvents::onPostExecute, $event);
        $collection = $event->getCollection();
        $options    = $event->getOptions();

        $this->persist($collection);

        $event = new FixtureCollectionEvent($this, $collection, $options);
        $this->eventDispatcher->dispatch(FixtureEvents::onPostPersist, $event);
    }

    /**
     *
     * @param FixtureCollection $collection
     * @param array|string $tags
     */
    protected function filterByTags(FixtureCollection $collection, $tags)
    {
        if (!is_array($tags)) {
            $tags = array($tags);
        }

        /* @var $fixture Fixture */
        foreach ($collection as $fixture) {

            $tags = $fixture->getProperties()->get('tags');
            if(!$tags || !is_array($tags)) {
                $collection->remove($fixture->getName());
                continue;
            }

            foreach ($tags as $tag) {
                if (in_array($tag, $tags)) {
                    continue 2;
                }
            }
            $collection->remove($fixture->getName());
        }
    }

    /**
     *
     * @param FixtureCollection $collection
     */
    protected function persist(FixtureCollection $collection)
    {
        foreach ($collection as $fixture) {
            foreach ($fixture as $data) {
                $this->persister->addObject($data->getObject());
            }
        }

        $this->persister->save();
    }

    /**
     *
     * @return FixtureManager
     */
    static public function createDefaultFixtureManager($objectManager)
    {
        $yamlLoader = new \DavidBadura\Fixtures\Loader\YamlLoader();
        $arrayLoader = new \DavidBadura\Fixtures\Loader\ArrayLoader();
        $jsonLoader = new \DavidBadura\Fixtures\Loader\JsonLoader();
        $loader = new \DavidBadura\Fixtures\Loader\ChainLoader(array($yamlLoader, $arrayLoader, $jsonLoader));

        $executor = \DavidBadura\Fixtures\Executor\Executor::createDefaultExecutor();

        if($objectManager instanceof \Doctrine\ODM\MongoDB\DocumentManager) {
            $persister = new \DavidBadura\Fixtures\Persister\MongoDBPersister($objectManager);
        } elseif($objectManager instanceof \Doctrine\Common\Persistence\ObjectManager) {
            $persister = new \DavidBadura\Fixtures\Persister\DoctrinePersister($objectManager);
        } else {
            throw new RuntimeException();
        }

        $eventDispatcher = new \Symfony\Component\EventDispatcher\EventDispatcher();

        return new self($loader, $executor, $persister, $eventDispatcher);
    }

}