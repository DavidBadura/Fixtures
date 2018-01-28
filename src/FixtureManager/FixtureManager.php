<?php declare(strict_types=1);

namespace DavidBadura\Fixtures\FixtureManager;

use DavidBadura\Fixtures\Event\FixtureCollectionEvent;
use DavidBadura\Fixtures\Event\FixtureEvent;
use DavidBadura\Fixtures\Executor\Executor;
use DavidBadura\Fixtures\Executor\ExecutorInterface;
use DavidBadura\Fixtures\Fixture\FixtureCollection;
use DavidBadura\Fixtures\Fixture\FixtureData;
use DavidBadura\Fixtures\FixtureEvents;
use DavidBadura\Fixtures\Loader;
use DavidBadura\Fixtures\Loader\LoaderInterface;
use DavidBadura\Fixtures\Persister\PersisterInterface;
use DavidBadura\Fixtures\ServiceProvider\ServiceProvider;
use DavidBadura\Fixtures\ServiceProvider\ServiceProviderInterface;
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
    private const SERVICE_PLACEHOLDER_PATTERN = '#<([^>]+)::([^>]+)\(([^>]*)\)>#';

    /**
     * {0..5}
     */
    private const MULTI_PLACEHOLDER_PATTERN = '#\{([0-9][0-9]*)\.\.([0-9][0-9]*)\}#';

    private $loader;
    private $executor;
    private $persister;
    private $serviceProvider;
    private $eventDispatcher;

    public function __construct(
        LoaderInterface $loader,
        ExecutorInterface $executor,
        PersisterInterface $persister,
        ServiceProviderInterface $serviceProvider = null,
        EventDispatcherInterface $eventDispatcher = null
    ) {
        $this->loader = $loader;
        $this->executor = $executor;
        $this->persister = $persister;

        $this->serviceProvider = $serviceProvider ?: new ServiceProvider();
        $this->eventDispatcher = $eventDispatcher ?: new EventDispatcher();
    }

    public function getLoader(): LoaderInterface
    {
        return $this->loader;
    }

    public function getExecutor(): ExecutorInterface
    {
        return $this->executor;
    }

    public function getPersister(): PersisterInterface
    {
        return $this->persister;
    }

    public function getServiceProvider(): ServiceProviderInterface
    {
        return $this->serviceProvider;
    }

    public function getEventDispatcher(): EventDispatcherInterface
    {
        return $this->eventDispatcher;
    }

    public function addService(string $name, $service): void
    {
        $this->serviceProvider->add($name, $service);
    }

    public function hasService(string $name): bool
    {
        return $this->serviceProvider->has($name);
    }

    public function removeService(string $name)
    {
        $this->serviceProvider->remove($name);
    }

    public function getService(string $name)
    {
        return $this->serviceProvider->get($name);
    }

    public function load($path = null, array $options = [])
    {
        $event = new FixtureEvent($this, $options);
        $this->eventDispatcher->dispatch(FixtureEvents::onPreLoad, $event);
        $options = $event->getOptions();

        $collection = $this->loader->load($path);

        $event = new FixtureCollectionEvent($this, $collection, $options);
        $this->eventDispatcher->dispatch(FixtureEvents::onPreExecute, $event);
        $collection = $event->getCollection();
        $options = $event->getOptions();

        $this->replaceMultiPlaceholder($collection);
        $this->replaceServicePlaceholder($collection);

        $event = new FixtureCollectionEvent($this, $collection, $options);
        $this->eventDispatcher->dispatch(FixtureEvents::onPreExecute, $event);
        $collection = $event->getCollection();
        $options = $event->getOptions();

        $this->executor->execute($collection);

        $event = new FixtureCollectionEvent($this, $collection, $options);
        $this->eventDispatcher->dispatch(FixtureEvents::onPostExecute, $event);
        $collection = $event->getCollection();
        $options = $event->getOptions();

        if (isset($options['dry_run']) && $options['dry_run'] == true) {
            return;
        }

        $this->persist($collection);

        $event = new FixtureCollectionEvent($this, $collection, $options);
        $this->eventDispatcher->dispatch(FixtureEvents::onPostPersist, $event);
    }

    protected function persist(FixtureCollection $collection)
    {
        foreach ($collection as $fixture) {
            foreach ($fixture as $data) {
                $this->persister->persist($data);
            }
        }

        $this->persister->flush();
    }

    protected function replaceServicePlaceholder(FixtureCollection $collection)
    {
        $provider = $this->serviceProvider;

        foreach ($collection as $fixture) {
            foreach ($fixture as $fixtureData) {
                $data = $fixtureData->getData();

                array_walk_recursive($data, function (&$item, &$key) use ($provider) {
                    $matches = [];
                    if (preg_match(FixtureManager::SERVICE_PLACEHOLDER_PATTERN, $item, $matches)) {
                        $service = $provider->get($matches[1]);
                        $attributes = explode(',', $matches[3]);
                        $item = call_user_func_array([$service, $matches[2]], $attributes);
                    }
                });

                $fixtureData->setData($data);
            }
        }
    }

    protected function replaceMultiPlaceholder(FixtureCollection $collection)
    {
        foreach ($collection as $fixture) {
            foreach ($fixture as $fixtureData) {
                $matches = [];
                if (preg_match(FixtureManager::MULTI_PLACEHOLDER_PATTERN, $fixtureData->getKey(), $matches)) {
                    $from = $matches[1];
                    $to = $matches[2];

                    if ($from > $to) {
                        throw new \RuntimeException();
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

    public static function createDefaultFixtureManager($objectManager): self
    {
        $matchLoader = new Loader\MatchLoader();
        $matchLoader
            ->add(new Loader\PhpLoader(), '*.php')
            ->add(new Loader\YamlLoader(), '*.yml')
            ->add(new Loader\JsonLoader(), '*.json')
            ->add(new Loader\TomlLoader(), '*.toml');

        $loader = new Loader\DirectoryLoader(
            new Loader\FilterLoader($matchLoader)
        );

        $executor = Executor::createDefaultExecutor();

        if ($objectManager instanceof PersisterInterface) {
            $persister = $objectManager;
        } elseif ($objectManager instanceof \Doctrine\ODM\MongoDB\DocumentManager) {
            $persister = new \DavidBadura\Fixtures\Persister\MongoDBPersister($objectManager);
        } elseif ($objectManager instanceof \Doctrine\Common\Persistence\ObjectManager) {
            $persister = new \DavidBadura\Fixtures\Persister\DoctrinePersister($objectManager);
        } else {
            throw new \RuntimeException();
        }

        return new self($loader, $executor, $persister);
    }
}
