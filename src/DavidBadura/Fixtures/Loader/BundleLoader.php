<?php

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
    public function __construct(LoaderInterface $loader, KernelInterface $kernel, array $bundles = array())
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
            throw new \RuntimeException('Fixtures not found');
        }

        $paths = array();

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
     * @return FixtureCollection
     */
    public function load($path, array $options = array())
    {
        if ($path === null) {
            $paths = $this->getFixturesByBundles();
        } elseif (!is_array($path)) {
            $paths = array($path);
        }

        $collection = new FixtureCollection();

        foreach ($paths as $p) {
            $collection->merge($this->loader->load($p));
        }

        return $collection;
    }

}
