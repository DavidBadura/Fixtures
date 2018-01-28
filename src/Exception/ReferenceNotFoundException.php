<?php declare(strict_types=1);

namespace DavidBadura\Fixtures\Exception;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class ReferenceNotFoundException extends RuntimeException
{
    /**
     *
     * @param string $name
     * @param string $key
     */
    public function __construct($name, $key)
    {
        parent::__construct($name, $key, sprintf("Fixture data %s:%s does not exist", $name, $key));
        $this->name = $name;
        $this->key = $key;
    }
}
