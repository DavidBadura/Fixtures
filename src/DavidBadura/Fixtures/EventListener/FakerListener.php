<?php

namespace DavidBadura\Fixtures\EventListener;

use DavidBadura\Fixtures\Fixture\FixtureCollection;
use DavidBadura\Fixtures\Fixture\FixtureData;
use DavidBadura\Fixtures\Event\PreExecuteEvent;
use Faker\Generator;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class FakerListener
{

    /**
     * <name()>
     * <numberBetween(5,6)>
     */
    const FAKER_PLACEHOLDER_PATTERN = '#<([^>]+)\(([^>]*)\)>#';

    /**
     *
     * {0..5}
     */
    const MULTI_PLACEHOLDER_PATTERN = '#\{([0-9][0-9]*)\.\.([0-9][0-9]*)\}#';

    /**
     *
     * @var Generator
     */
    protected $faker;

    /**
     *
     * @param Generator $faker
     */
    public function __construct(Generator $faker)
    {
        $this->faker = $faker;
    }

    /**
     *
     * @param PreExecuteEvent $event
     */
    public function onPreExecuteEvent(PreExecuteEvent $event)
    {
        $collection = $event->getCollection();

        $this->replaceMultiPlaceholder($collection);
        $this->replaceFakerPlaceholder($collection);
    }

    protected function replaceFakerPlaceholder(FixtureCollection $collection)
    {
        $faker = $this->faker;

        foreach($collection as $fixture) {
            foreach ($fixture as $fixtureData) {
                $data = $fixtureData->getData();

                array_walk_recursive($data, function(&$item, &$key) use ($faker) {
                    $matches = array();
                    if (preg_match(FakerListener::FAKER_PLACEHOLDER_PATTERN, $item, $matches)) {
                        $attributes = explode(',', $matches[2]);
                        $item = call_user_func_array(array($faker, $matches[1]), $attributes);
                    }
                });

                $fixtureData->setData($data);

            }
        }

    }

    protected function replaceMultiPlaceholder(FixtureCollection $collection)
    {
        foreach ($collection as $fixture) {
            foreach ($fixture as $fixtureData) {
                $matches = array();
                if (preg_match(FakerListener::MULTI_PLACEHOLDER_PATTERN, $fixtureData->getKey(), $matches)) {

                    $from = $matches[1];
                    $to = $matches[2];

                    if ($from > $to) {
                        throw new \Exception();
                    }

                    for ($i = $from; $i <= $to; $i++) {
                        $newKey = str_replace($matches[0], $i, $fixtureData->getKey());
                        $newFixture = new FixtureData($newKey, $fixtureData->getData());
                        $fixture->add($newFixture);
                    }

                    $fixture->remove($fixtureData);
                }
            }
        }
    }

}
