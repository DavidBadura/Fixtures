<?php declare(strict_types=1);

namespace DavidBadura\Fixtures\EventListener;

use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use DavidBadura\Fixtures\Event\FixtureCollectionEvent;
use DavidBadura\Fixtures\Fixture\FixtureData;
use DavidBadura\Fixtures\Fixture\FixtureCollection;
use DavidBadura\Fixtures\Exception\RuntimeException;

/**
 * @author David Badura <d.badura@gmx.de>
 */
class ExpressionLanguageListener
{
    private $expressionLanguage;

    public function __construct(ExpressionLanguage $expressionLanguage)
    {
        $this->expressionLanguage = $expressionLanguage;
    }

    public function geExpressionLanguage(): ExpressionLanguage
    {
        return $this->expressionLanguage;
    }

    public function onPreExecute(FixtureCollectionEvent $event): void
    {
        $collection = $event->getCollection();

        foreach ($collection as $name => $fixture) {
            foreach ($fixture as $key => $data) {
                try {
                    $this->executeExpression($data, $collection);
                } catch (\Exception $e) {
                    throw new RuntimeException(
                        $name,
                        $key,
                        sprintf("Expression language error '%s'", $e->getMessage()),
                        0,
                        $e
                    );
                }
            }
        }
    }

    private function executeExpression(FixtureData $fixtureData, FixtureCollection $collection): void
    {
        $data = $fixtureData->getData();

        $lang = $this->expressionLanguage;

        array_walk_recursive($data, function (&$value, $key) use ($lang, $collection) {
            if (preg_match('/^@expr\((.*)\)$/', $value, $hit)) {
                $expr = substr($value, 6, -1);

                $value = $lang->evaluate($expr, [
                    'collection' => $collection,
                ]);
            }
        });

        $fixtureData->setData($data);
    }
}
