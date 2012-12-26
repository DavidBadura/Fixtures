<?php

namespace DavidBadura\Fixtures\EventListener;

use DavidBadura\Fixtures\Event\PostFixtureLoadEvent;
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
     * @param PostExecuteEvent $event
     */
    public function onPostFixtureLoad(PostFixtureLoadEvent $event)
    {
        $data = $event->getData();

        $data = $this->replaceMultiPlaceholder($data);
        $data = $this->replaceFakerPlaceholder($data);

        $event->setData($data);
    }

    protected function replaceFakerPlaceholder($data)
    {
        $faker = $this->faker;

        array_walk_recursive($data, function(&$item, &$key) use ($faker) {
                $matches = array();
                if (preg_match(FakerListener::FAKER_PLACEHOLDER_PATTERN, $item, $matches)) {
                    $attributes = explode(',', $matches[2]);
                    $item = call_user_func_array(array($faker, $matches[1]), $attributes);
                }
            });

        return $data;
    }

    protected function replaceMultiPlaceholder($fixtures)
    {
        foreach ($fixtures as $key1 => $fixture) {
            foreach ($fixture['data'] as $key2 => $data) {
                $matches = array();
                if (preg_match(FakerListener::MULTI_PLACEHOLDER_PATTERN, $key2, $matches)) {

                    $from = $matches[1];
                    $to = $matches[2];

                    if ($from > $to) {
                        throw new \Exception();
                    }

                    for ($i = $from; $i <= $to; $i++) {
                        $fixtures[$key1]['data'][str_replace($matches[0], $i, $key2)] = $data;
                    }

                    unset($fixtures[$key1]['data'][$key2]);
                }
            }
        }

        array_walk_recursive($fixtures, function(&$item, &$key) {
                $matches = array();
                if (preg_match(FakerListener::MULTI_PLACEHOLDER_PATTERN, $item, $matches)) {

                    $from = $matches[1];
                    $to = $matches[2];

                    if ($from > $to) {
                        throw new \Exception();
                    }

                    $item = str_replace($matches[0], rand($from, $to), $item);
                }
            });

        return $fixtures;
    }

}
