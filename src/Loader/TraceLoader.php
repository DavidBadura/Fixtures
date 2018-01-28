<?php declare(strict_types=1);

namespace DavidBadura\Fixtures\Loader;

use DavidBadura\Fixtures\Fixture\FixtureCollection;

/**
 * @author David Badura <d.badura@gmx.de>
 */
class TraceLoader implements LoaderInterface
{
    private $loader;
    private $trace;

    public function __construct(LoaderInterface $loader)
    {
        $this->loader = $loader;
        $this->trace = [];
    }

    public function reset(): void
    {
        $this->trace = [];
    }

    public function getTrace(): array
    {
        return $this->trace;
    }

    public function load($path, array $options = []): FixtureCollection
    {
        if (is_array($path)) {
            $this->trace = array_merge($this->trace, $path);
        } else {
            $this->trace[] = $path;
        }

        return $this->loader->load($path);
    }
}
