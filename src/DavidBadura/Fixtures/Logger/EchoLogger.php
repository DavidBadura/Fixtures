<?php

namespace DavidBadura\Fixtures\Logger;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class EchoLogger implements Logger
{

    /**
     *
     * @param string $message
     */
    public function log($message)
    {
        echo $message . '<br/>';
    }
    
    /**
     *
     * @param string $message
     */
    public function headline($message)
    {
        echo '<h1>' . $message . '</h1>';
    }

}