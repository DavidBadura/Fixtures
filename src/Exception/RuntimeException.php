<?php declare(strict_types=1);

namespace DavidBadura\Fixtures\Exception;

/**
 * @author David Badura <d.badura@gmx.de>
 */
class RuntimeException extends FixtureException
{
    protected $name;
    protected $key;

    public function __construct(
        string $name,
        string $key,
        string $message = '',
        int $code = 0,
        \Exception $parent = null
    ) {
        $message = sprintf('Error by @%s:%s : ', $name, $key).$message;

        parent::__construct($message, $code, $parent);

        $this->name = $name;
        $this->key = $key;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getKey(): string
    {
        return $this->key;
    }
}
