<?php

namespace DavidBadura\Fixtures\Loader;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class TraceLoader implements LoaderInterface
{

    /**
     *
     * @var LoaderInterface
     */
    private $loader;

    /**
     *
     * @var array
     */
    private $trace;

    /**
     *
     * @param LoaderInterface $loader
     */
    public function __construct(LoaderInterface $loader)
    {
        $this->loader = $loader;
        $this->trace = array();
    }

    /**
     *
     */
    public function reset()
    {
        $this->trace = array();
    }

    /**
     *
     * @return array
     */
    public function getTrace()
    {
        return $this->trace;
    }

    /**
     *
     * @param  string|array                                    $path
     * @param  array                                           $options
     * @return \DavidBadura\Fixtures\Fixture\FixtureCollection
     */
    public function load($path, array $options = array())
    {
        if (is_array($path)) {
            $this->trace = array_merge($this->trace, $path);
        } else {
            $this->trace[] = $path;
        }

        return $this->loader->load($path);
    }

}
