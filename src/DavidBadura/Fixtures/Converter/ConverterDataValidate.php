<?php

namespace DavidBadura\Fixtures\Converter;

use Symfony\Component\Config\Definition\Builder\NodeBuilder;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
interface ConverterDataValidate
{

    public function addNodeSchema(NodeBuilder $node);

}
