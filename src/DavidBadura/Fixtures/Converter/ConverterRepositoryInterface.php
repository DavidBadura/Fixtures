<?php

namespace DavidBadura\Fixtures\Converter;

/**
 *
 * @author David Badura <d.a.badura@gmail.com>
 */
interface ConverterRepositoryInterface
{

    public function addConverter(ConverterInterface $converter);

    public function hasConverter($name);

    public function getConverter($name);

    public function removeConverter($name);

}
