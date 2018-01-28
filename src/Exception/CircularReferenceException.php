<?php declare(strict_types=1);

namespace DavidBadura\Fixtures\Exception;

/**
 * @author David Badura <d.badura@gmx.de>
 */
class CircularReferenceException extends RuntimeException
{
    private $path;

    public function __construct(string $name, string $key, array $path)
    {
        parent::__construct(
            $name,
            $key,
            sprintf(
                'Circular reference detected for fixture "%s:%s", path: "%s -> %s:%s".',
                $name,
                $key,
                implode(' -> ', array_keys($path)),
                $name,
                $key
            )
        );

        $this->path = $path;
    }

    public function getPath(): array
    {
        return $this->path;
    }
}
