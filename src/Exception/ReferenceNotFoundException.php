<?php declare(strict_types=1);

namespace DavidBadura\Fixtures\Exception;

/**
 * @author David Badura <d.badura@gmx.de>
 */
class ReferenceNotFoundException extends RuntimeException
{
    public function __construct(string $name, string $key)
    {
        parent::__construct($name, $key, sprintf("Fixture data %s:%s does not exist", $name, $key));
    }
}
