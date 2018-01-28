<?php declare(strict_types=1);

namespace DavidBadura\Fixtures\Util;

/**
 * @author David Badura <d.badura@gmx.de>
 */
class Matcher
{
    public static function match(string $subject, string $pattern): bool
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
