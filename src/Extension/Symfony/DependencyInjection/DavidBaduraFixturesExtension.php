<?php declare(strict_types=1);

namespace DavidBadura\Fixtures\Extension\Symfony\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Definition\Processor;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 * @author Florian Eckerstorfer <florian@theroadtojoy.at>
 */
class DavidBaduraFixturesExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));

        $processor = new Processor();
        $configuration = new Configuration();

        $config = $processor->process($configuration->getConfigTree(), $configs);
        $loader->load('services.xml');

        if ($config['persister'] == 'orm') {
            $persister = $container->register(
                'davidbadura_fixtures.persister',
                'DavidBadura\Fixtures\Persister\DoctrinePersister'
            );
            $serviceId = ($config['persister_id']) ? $config['persister_id'] : 'doctrine.orm.entity_manager';
            $persister->addArgument(new Reference($serviceId));
        } elseif ($config['persister'] === 'odm') {
            $persister = $container->register(
                'davidbadura_fixtures.persister',
                'DavidBadura\Fixtures\Persister\MongoDBPersister'
            );
            $serviceId = ($config['persister_id']) ? $config['persister_id'] : 'doctrine.odm.mongodb.document_manager';
            $persister->addArgument(new Reference($serviceId));
        } else {
            throw new \Exception();
        }


        if (isset($config['bundles'])) {
            $fixtureLoader = $container->getDefinition('davidbadura_fixtures.loader_factory');
            $fixtureLoader->addArgument(array_unique($config['bundles']));
        }
    }
}
