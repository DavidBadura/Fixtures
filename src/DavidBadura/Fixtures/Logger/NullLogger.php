<?php

namespace DavidBadura\Fixtures\Logger;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class NullLogger implements Logger
{

    /**
     *
     * @param string $message
     */
    public function log($message)
    {
        // do nothing
    }

    /**
     *
     * @param string $message
     */
    public function headline($message)
    {
        // do nothing
    }

}