<?php

namespace DavidBadura\Fixtures;

use Symfony\Component\ExpressionLanguage\ExpressionLanguage as BaseExpressionLanguage;
use DavidBadura\Fixtures\Executor\ExecutorInterface;

/**
 *
 * @author David Badura <d.a.badura@gmail.com>
 */
class ExpressionLanguage extends BaseExpressionLanguage
{

    /**
     *
     * @var ExecutorInterface
     */
    protected $executor;

    /**
     *
     * @param ExecutorInterface
     */
    public function __construct(ExecutorInterface $executor)
    {
        parent::__construct();

        $this->executor = $executor;

        $this->register('object', function($name, $key) {
            return sprintf('object("%s", "%s")', $name, $key);
        }, function($arguments, $name, $key) use ($executor) {
            $collection = $arguments['collection'];
            $fixtureData = $collection->get($name)->get($key);

            if (!$object = $fixtureData->getObject()) {
                $object = $executor->createObject($collection, $name, $key);
            }

            return $object;
        });

   }

}
