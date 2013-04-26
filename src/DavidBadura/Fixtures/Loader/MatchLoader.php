<?php

namespace DavidBadura\Fixtures\Loader;

use DavidBadura\Fixtures\Fixture\FixtureCollection;
use DavidBadura\Fixtures\Exception\RuntimeException;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class MatchLoader implements LoaderInterface
{

    /**
     *
     * @var array
     */
    private $mapping = array();

    /**
     *
     * @param LoaderInterface $loader
     * @return self
     */
    public function add(LoaderInterface $loader, $pattern)
    {
        $this->mapping[] = array(
            'loader' => $loader,
            'pattern' => $pattern
        );

        return $this;
    }

    /**
     *
     * @param type $path
     * @return FixtureCollection
     */
    public function load($path, array $options = array())
    {
        foreach ($this->mapping as $mapping) {

            if (!$this->match($path, $mapping['pattern'])) {
                continue;
            }

            return $mapping['loader']->load($path, $options);
        }

        throw new RuntimeException(sprintf('not matching for "%s"', $path));
    }

    /**
     *
     * @param string $path
     * @param string $pattern
     * @return boolean
     */
    protected function match($path, $pattern)
    {
        $expr = preg_replace_callback('/[\\\\^$.[\\]|()?*+{}\\-\\/]/', function($matches) {
                switch ($matches[0]) {
                    case '*':
                        return '.*';
                    case '?':
                        return '.';
                    default:
                        return '\\' . $matches[0];
                }
            }, $pattern);

        return (bool) preg_match('/' . $expr . '/', $path);
    }

}