<?php declare(strict_types=1);

namespace DavidBadura\Fixtures\Extension\Symfony;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use DavidBadura\Fixtures\Extension\Symfony\DependencyInjection\Compiler\ConverterPass;
use DavidBadura\Fixtures\Extension\Symfony\DependencyInjection\Compiler\FakerPass;

/**
 * @author David Badura <d.badura@gmx.de>
 */
class DavidBaduraFixturesBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new ConverterPass());
        $container->addCompilerPass(new FakerPass());
    }
}
