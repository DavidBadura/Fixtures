<?php declare(strict_types=1);

namespace DavidBadura\Fixtures\Extension\Symfony\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class FakerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if ($container->hasDefinition('davidbadura_faker.faker')) {
            $faker = $container->getDefinition('davidbadura_faker.faker');
            $faker->addTag('davidbadura_fixtures.service');
        }
    }
}
