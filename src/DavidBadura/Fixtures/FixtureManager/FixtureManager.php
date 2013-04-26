<?php

namespace DavidBadura\Fixtures\FixtureManager;

use DavidBadura\Fixtures\Exception\RuntimeException;
use DavidBadura\Fixtures\Fixture\FixtureData;
use DavidBadura\Fixtures\Fixture\FixtureCollection;
use DavidBadura\Fixtures\Loader;
use DavidBadura\Fixtures\Loader\LoaderInterface;
use DavidBadura\Fixtures\Executor\ExecutorInterface;
use DavidBadura\Fixtures\Persister\PersisterInterface;
use DavidBadura\Fixtures\ServiceProvider\ServiceProvider;
use DavidBadura\Fixtures\ServiceProvider\ServiceProviderInterface;
use DavidBadura\Fixtures\FixtureEvents;
use DavidBadura\Fixtures\Event\FixtureEvent;
use DavidBadura\Fixtures\Event\FixtureCollectionEvent;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class FixtureManager implements FixtureManagerInterface
{

    /**
     * <service::name()>
     * <service::numberBetween(5,6)>
     */
    const SERVICE_PLACEHOLDER_PATTERN = '#<([^>]+)::([^>]+)\(([^>]*)\)>#';

    /**
     *
     * {0..5}
     */
    const MULTI_PLACEHOLDER_PATTERN = '#\{([0-9][0-9]*)\.\.([0-9][0-9]*)\}#';

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
     * @var ServiceProviderInterface
     */
    private $serviceProvider;

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
        ServiceProviderInterface $serviceProvider = null,
        EventDispatcherInterface $eventDispatcher = null)
    {
        $this->loader = $loader;
        $this->executor = $executor;
        $this->persister = $persister;

        $this->serviceProvider = ($serviceProvider) ?: new ServiceProvider();
        $this->eventDispatcher = ($eventDispatcher) ?: new EventDispatcher();

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
     * @return ServiceProviderInterface
     */
    public function getServiceProvider()
    {
        return $this->serviceProvider;
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
     * @param string $name
     * @param object $service
     */
    public function addService($name, $service)
    {
        $this->serviceProvider->add($name, $service);
    }

    /**
     *
     * @param string $name
     * @return boolean
     */
    public function hasService($name)
    {
        return $this->serviceProvider->has($name);
    }

    /**
     *
     * @param string $name
     */
    public function removeService($name)
    {
        $this->serviceProvider->remove($name);
    }

    /**
     *
     * @param string $name
     * @return object
     */
    public function getService($name)
    {
        return $this->serviceProvider->get($name);
    }

    /**
     *
     * @param string $path
     * @param array $options
     */
    public function load($path = null, array $options = array())
    {
        $event = new FixtureEvent($this, $options);
        $this->eventDispatcher->dispatch(FixtureEvents::onPreLoad, $event);
        $options = $event->getOptions();

        $collection = $this->loader->load($path);

        $event = new FixtureCollectionEvent($this, $collection, $options);
        $this->eventDispatcher->dispatch(FixtureEvents::onPreExecute, $event);
        $collection = $event->getCollection();
        $options    = $event->getOptions();

        $this->replaceMultiPlaceholder($collection);
        $this->replaceServicePlaceholder($collection);

        $event = new FixtureCollectionEvent($this, $collection, $options);
        $this->eventDispatcher->dispatch(FixtureEvents::onPreExecute, $event);
        $collection = $event->getCollection();
        $options    = $event->getOptions();

        $this->executor->execute($collection);

        $event = new FixtureCollectionEvent($this, $collection, $options);
        $this->eventDispatcher->dispatch(FixtureEvents::onPostExecute, $event);
        $collection = $event->getCollection();
        $options    = $event->getOptions();

        if(isset($options['dry_run']) && $options['dry_run'] == true) {
            return;
        }

        $this->persist($collection);

        $event = new FixtureCollectionEvent($this, $collection, $options);
        $this->eventDispatcher->dispatch(FixtureEvents::onPostPersist, $event);
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
     * @param FixtureCollection $collection
     */
    protected function replaceServicePlaceholder(FixtureCollection $collection)
    {
        $provider = $this->serviceProvider;

        foreach($collection as $fixture) {
            foreach ($fixture as $fixtureData) {
                $data = $fixtureData->getData();

                array_walk_recursive($data, function(&$item, &$key) use ($provider) {
                    $matches = array();
                    if (preg_match(FixtureManager::SERVICE_PLACEHOLDER_PATTERN, $item, $matches)) {
                        $service = $provider->get($matches[1]);
                        $attributes = explode(',', $matches[3]);
                        $item = call_user_func_array(array($service, $matches[2]), $attributes);
                    }
                });

                $fixtureData->setData($data);

            }
        }

    }

    /**
     *
     * @param FixtureCollection $collection
     * @throws \Exception
     */
    protected function replaceMultiPlaceholder(FixtureCollection $collection)
    {
        foreach ($collection as $fixture) {
            foreach ($fixture as $fixtureData) {
                $matches = array();
                if (preg_match(FixtureManager::MULTI_PLACEHOLDER_PATTERN, $fixtureData->getKey(), $matches)) {

                    $from = $matches[1];
                    $to = $matches[2];

                    if ($from > $to) {
                        throw new \Exception();
                    }

                    for ($i = $from; $i <= $to; $i++) {
                        $newKey = str_replace($matches[0], $i, $fixtureData->getKey());
                        $newFixture = new FixtureData($newKey, $fixtureData->getData());
                        $fixture->add($newFixture);
                    }

                    $fixture->remove($fixtureData);
                }
            }
        }
    }

    /**
     *
     * @param object $objectManager
     * @return FixtureManager
     */
    static public function createDefaultFixtureManager($objectManager)
    {
        $matchLoader = new Loader\MatchLoader();
        $matchLoader
            ->add(new Loader\ArrayLoader(), '*.php')
            ->add(new Loader\YamlLoader(), '*.yml')
            ->add(new Loader\JsonLoader(), '*.json')
            ->add(new Loader\TomlLoader(), '*.toml')
        ;

        $loader = new Loader\DirectoryLoader(
            new Loader\FilterLoader($matchLoader)
        );

        $executor = \DavidBadura\Fixtures\Executor\Executor::createDefaultExecutor();

        if($objectManager instanceof PersisterInterface) {
            $persister = $objectManager;
        } elseif($objectManager instanceof \Doctrine\ODM\MongoDB\DocumentManager) {
            $persister = new \DavidBadura\Fixtures\Persister\MongoDBPersister($objectManager);
        } elseif($objectManager instanceof \Doctrine\Common\Persistence\ObjectManager) {
            $persister = new \DavidBadura\Fixtures\Persister\DoctrinePersister($objectManager);
        } else {
            throw new RuntimeException();
        }

        return new self($loader, $executor, $persister);
    }

}