<?php

namespace DavidBadura\Fixtures\Exception;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class RuntimeException extends FixtureException
{

    /**
     *
     * @var string
     */
    protected $name;

    /**
     *
     * @var string
     */
    protected $key;

    /**
     *
     * @param string $name
     * @param string $key
     * @param string $message
     * @param string $code
     * @param \Exception $parent
     */
    public function __construct($name, $key, $message = "", $code = null, \Exception $parent = null)
    {
        $message = sprintf('Error by @%s:%s : ', $name, $key) . $message;

        parent::__construct($message, $code, $parent);

        $this->name = $name;
        $this->key = $key;
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

}
