<?php declare(strict_types=1);

namespace DavidBadura\Fixtures\Converter;

/**
 *
 * @author David Badura <d.a.badura@gmail.com>
 */
interface ConverterRepositoryInterface
{
    /**
     * @param ConverterInterface $converter
     */
    public function addConverter(ConverterInterface $converter);

    /**
     * @param string $name
     * @return bool
     */
    public function hasConverter($name);

    /**
     * @param string $name
     * @return ConverterInterface
     */
    public function getConverter($name);

    /**
     * @param string $name
     */
    public function removeConverter($name);
}
