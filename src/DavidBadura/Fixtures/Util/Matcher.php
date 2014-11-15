<?php

namespace DavidBadura\Fixtures\Util;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class Matcher
{
    /**
     *
     * @param $subject
     * @param  string $pattern
     * @return boolean
     */
    public static function match($subject, $pattern)
    {
        $expr = preg_replace_callback('/[\\\\^$.[\\]|()?*+{}\\-\\/]/', function ($matches) {
            switch ($matches[0]) {
                case '*':
                    return '.*';
                case '?':
                    return '.';
                default:
                    return '\\' . $matches[0];
            }
        }, $pattern);

        return (bool)preg_match('/' . $expr . '/', $subject);
    }
}
