<?php declare(strict_types=1);

namespace DavidBadura\Fixtures\FixtureManager;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
interface FixtureManagerInterface
{
    /**
     *
     * @param string|array $path
     * @param array $options
     */
    public function load($path = null, array $options = []);
}
