<?php declare(strict_types=1);

namespace DavidBadura\Fixtures\Converter;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
interface ConverterRepositoryInterface
{
    public function addConverter(ConverterInterface $converter): void;

    public function hasConverter(string $name): bool;

    public function getConverter(string $name): ConverterInterface;

    public function removeConverter(string $name): void;
}
