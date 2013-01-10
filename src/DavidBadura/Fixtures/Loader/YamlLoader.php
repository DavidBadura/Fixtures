<?php

namespace DavidBadura\Fixtures\Loader;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class YamlLoader implements LoaderInterface
{

    /**
     *
     * @param  mixed     $path
     * @return Fixture[]
     */
    public function load($path)
    {
        $finder = new Finder();
        $finder->in($path)->name($this->getPattern());

        $fixtures = array();
        foreach ($finder->files() as $file) {
            $data = Yaml::parse($file->getPathname());
            $fixtures = array_merge_recursive($fixtures, $data);
        }

        return $fixtures;
    }

    /**
     *
     * @return string
     */
    public function getPattern()
    {
        return '*.yml';
    }

}