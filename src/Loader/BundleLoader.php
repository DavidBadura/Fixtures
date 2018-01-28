<?php declare(strict_types=1);

namespace DavidBadura\Fixtures\Loader;

use Symfony\Component\HttpKernel\KernelInterface;
use DavidBadura\Fixtures\Fixture\FixtureCollection;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class BundleLoader implements LoaderInterface
{
    /**
     *
     * @var LoaderInterface
     */
    private $loader;

    /**
     *
     * @var KernelInterface
     */
    private $kernel;

    /**
     *
     * @var array
     */
    private $bundles;

    /**
     *
     * @param LoaderInterface $loader
     * @param KernelInterface $kernel
     * @param array $bundles
     */
    public function __construct(LoaderInterface $loader, KernelInterface $kernel, array $bundles = [])
    {
        $this->loader = $loader;
        $this->kernel = $kernel;
        $this->bundles = $bundles;
    }

    /**
     *
     * @return array
     */
    protected function getFixturesByBundles()
    {
        if (empty($this->bundles)) {
            throw new \RuntimeException('Bundles are not defined');
        }

        $paths = [];

        foreach ($this->bundles as $name) {
            $bundle = $this->kernel->getBundle($name);

            if (!$bundle) {
                throw new \RuntimeException(sprintf('unknown bundle %s', $bundle));
            }

            $paths[] = $bundle->getPath() . '/Resources/fixtures';
        }

        return $paths;
    }

    /**
     *
     * @param  string|array $path
     * @param array $options
     * @return FixtureCollection
     */
    public function load($path, array $options = [])
    {
        if (empty($path)) {
            $path = $this->getFixturesByBundles();
        } elseif (!is_array($path)) {
            $path = [$path];
        }

        $collection = new FixtureCollection();

        foreach ($path as $p) {
            $collection->merge($this->loader->load($p));
        }

        return $collection;
    }
}
