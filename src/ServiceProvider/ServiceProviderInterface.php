<?php declare(strict_types=1);

namespace DavidBadura\Fixtures\ServiceProvider;

/**
 * @author David Badura <d.badura@gmx.de>
 */
interface ServiceProviderInterface
{
    public function get(string $name);

    public function add(string $name, $object): void;

    public function has(string $name): bool;

    public function remove(string $name): void;
}
