<?php

namespace DavidBadura\Fixtures;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\HttpKernel\KernelInterface;
use DavidBadura\Fixtures\Logger\Logger;
use DavidBadura\Fixtures\Logger\NullLogger;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class FixtureLoader
{

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
     * @param KernelInterface $kernel
     * @param array           $bundles
     */
    public function __construct(KernelInterface $kernel = null, array $bundles = array())
    {
        $this->kernel = $kernel;
        $this->bundles = $bundles;
    }

    /**
     *
     * @return array
     */
    protected function getFixturesByBundles()
    {
        if(!$this->kernel || empty($this->bundles)) {
            return array();
        }

        $paths = array();

        foreach ($this->bundles as $name) {
            $bundle = $this->kernel->getBundle($name);
            $paths[] = $bundle->getPath() . '/Resources/fixtures';
        }

        return $paths;
    }

    /**
     *
     * @param  mixed     $path
     * @param  Logger    $logger
     * @return Fixture[]
     */
    public function loadFixtures($path = null, Logger $logger = null)
    {
        if(!$logger) {
            $logger = new NullLogger();
        }

        $path = (empty($path)) ? $this->getFixturesByBundles() : $path ;

        $finder = new Finder();
        $finder->in($path)->name('*.yml');

        $fixtures = array();
        foreach ($finder->files() as $file) {
            $logger->log($file->getPathname());
            $data = Yaml::parse($file->getPathname());
            if (is_array($data)) {
                $fixtures = array_merge_recursive($fixtures, $data);
            }
        }

        return $fixtures;
    }

}
