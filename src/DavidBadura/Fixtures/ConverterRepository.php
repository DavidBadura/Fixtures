<?php

namespace DavidBadura\Fixtures;

use DavidBadura\Fixtures\FixtureConverter\FixtureConverterInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpKernel\KernelInterface;
use DavidBadura\Fixtures\Exception\FixtureException;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class ConverterRepository
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
     * @var boolean
     */
    private $loaded = false;

    /**
     *
     * @var FixtureConverterInterface[]
     */
    private $converters = array();

    /**
     *
     * @var array
     */
    private $classes = array();

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
     * @param array $converters
     */
    public function addConverters(array $converters = array())
    {
        foreach ($converters as $converter) {
            $this->addConverter($converter);
        }
    }

    /**
     *
     * @param  FixtureConverterInterface                  $converter
     * @return \DavidBadura\Fixtures\FixtureManager
     * @throws \Exception
     */
    public function addConverter(FixtureConverterInterface $converter)
    {
        $name = $converter->getName();
        if (isset($this->converters[$name])) {
            throw new FixtureException(sprintf('Converter with the name "%s" already exists', $name));
        }

        $this->converters[$name] = $converter;
        $this->classes[get_class($converter)] = true;

        return $this;
    }

    /**
     *
     * @param  string  $name
     * @return boolean
     */
    public function hasConverter($name)
    {
        $this->init();

        return isset($this->converters[$name]);
    }

    /**
     *
     * @param  string                    $name
     * @return FixtureConverterInterface
     * @throws \Exception
     */
    public function getConverter($name)
    {
        $this->init();
        if (!isset($this->converters[$name])) {
            return null;
        }

        return $this->converters[$name];
    }

    /**
     *
     * @param  string                                     $name
     * @return \DavidBadura\Fixtures\FixtureManager
     * @throws \Exception
     */
    public function removeConverter($name)
    {
        $this->init();
        if (isset($this->converters[$name])) {
            unset($this->converters[$name]);
            unset($this->classes[get_class($this->converters[$name])]);
        }

        return $this;
    }

    /**
     *
     *
     */
    public function init()
    {
        if ($this->loaded)

            return;

        if (!$this->kernel || empty($this->bundles)) {
            $this->loaded = true;

            return;
        }

        $paths = array();

        foreach ($this->bundles as $name) {
            $bundle = $this->kernel->getBundle($name);

            if (file_exists($bundle->getPath() . '/FixtureConverter')) {
                $paths[] = $bundle->getPath() . '/FixtureConverter';
            }
        }

        if (empty($paths)) {
            $this->loaded = true;

            return;
        }

        $finder = new Finder();
        $finder->in($paths)->name('*Converter.php');

        foreach ($finder->files() as $file) {
            require_once $file->getRealpath();
        }

        $declared = get_declared_classes();

        foreach ($declared as $class) {

            if (isset($this->classes[$class]) || $this->isTransient($class)) {
                continue;
            }

            $converter = new $class;
            $this->addConverter($converter);
        }

        $this->loaded = true;
    }

    /**
     *
     * @param  string  $className
     * @return boolean
     */
    public function isTransient($className)
    {
        $rc = new \ReflectionClass($className);
        if ($rc->isAbstract())

            return true;

        $interfaces = class_implements($className);

        return in_array('DavidBadura\Fixtures\FixtureConverter\FixtureConverterInterface', $interfaces) ? false : true;
    }

}
