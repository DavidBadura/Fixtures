<?php declare(strict_types=1);

namespace DavidBadura\Fixtures\Loader;

use DavidBadura\Fixtures\Fixture\FixtureCollection;
use Symfony\Component\Finder\Finder;

/**
 * @author David Badura <d.badura@gmx.de>
 */
class DirectoryLoader implements LoaderInterface
{
    private $loader;

    public function __construct(LoaderInterface $loader)
    {
        $this->loader = $loader;
    }

    public function load($path, array $options = []): FixtureCollection
    {
        if (!file_exists($path)) {
            throw new \RuntimeException(sprintf('"%s" dir or file not found', $path));
        }

        if (is_file($path)) {
            return $this->loader->load($path, $options);
        }

        $finder = new Finder();
        $finder->in($path)->files();

        $collection = new FixtureCollection();

        foreach ($finder as $file) {
            $col = $this->loader->load(realpath($file->getPathname()), $options);
            $collection->merge($col);
        }

        return $collection;
    }
}
