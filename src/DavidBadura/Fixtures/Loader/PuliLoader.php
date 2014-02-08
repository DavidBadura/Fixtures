<?php

namespace DavidBadura\Fixtures\Loader;

use Webmozart\Puli\Locator\ResourceLocatorInterface;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class PuliLoader implements LoaderInterface
{

    /**
     *
     * @var LoaderInterface
     */
    protected $loader;

    /**
     *
     * @var ResourceLocatorInterface
     */
    protected $locator;

    /**
     *
     * @param ResourceLocatorInterface $locator
     * @param LoaderInterface $loader
     */
    public function __construct(ResourceLocatorInterface $locator, LoaderInterface $loader)
    {
        $this->locator = $locator;
        $this->loader  = $loader;
    }

    /**
     *
     * @param  mixed             $path
     * @return FixtureCollection
     */
    public function load($path, array $options = array())
    {
        if (isset($options['puli_tag']) && $options['puli_tag']) {
            $realPaths = array();
            $resources = $this->locator->getByTag($path);

            foreach ($resources as $resource) {
                $realPaths[] = $resource->getPath();
            }
        } else {
            $realPaths = $this->locator->get($path)->getPath();
        }

        return $this->loader->load($realPaths, $options);
    }
}
