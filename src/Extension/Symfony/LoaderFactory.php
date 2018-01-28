<?php declare(strict_types=1);

namespace DavidBadura\Fixtures\Extension\Symfony;

use DavidBadura\Fixtures\Loader\BundleLoader;
use DavidBadura\Fixtures\Loader\DirectoryLoader;
use DavidBadura\Fixtures\Loader\FilterLoader;
use DavidBadura\Fixtures\Loader\JsonLoader;
use DavidBadura\Fixtures\Loader\LoaderInterface;
use DavidBadura\Fixtures\Loader\MatchLoader;
use DavidBadura\Fixtures\Loader\PhpLoader;
use DavidBadura\Fixtures\Loader\TomlLoader;
use DavidBadura\Fixtures\Loader\YamlLoader;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * @author David Badura <d.badura@gmx.de>
 */
class LoaderFactory
{
    protected $kernel;

    protected $bundles;

    public function __construct(KernelInterface $kernel, array $bundles = [])
    {
        $this->kernel = $kernel;
        $this->bundles = $bundles;
    }

    public function create(): LoaderInterface
    {
        $matchLoader = new MatchLoader();
        $matchLoader
            ->add(new PhpLoader(), '*.php')
            ->add(new YamlLoader(), '*.yml')
            ->add(new JsonLoader(), '*.json')
            ->add(new TomlLoader(), '*.toml');

        $directoryLoader = new DirectoryLoader(
            new FilterLoader($matchLoader)
        );

        return new BundleLoader($directoryLoader, $this->kernel, $this->bundles);
    }
}
