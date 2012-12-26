<?php

namespace DavidBadura\Fixtures\Logger;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
interface Logger
{

    /**
     *
     * @param string $message
     */
    public function headline($message);

    /**
     *
     * @param string $message
     */
    public function log($message);

}