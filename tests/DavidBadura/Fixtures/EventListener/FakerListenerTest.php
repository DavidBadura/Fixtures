<?php

namespace DavidBadura\Fixtures\EventListener;

use DavidBadura\Fixtures\EventListener\FakerListener;
use DavidBadura\Fixtures\Event\PostFixtureLoadEvent;
use DavidBadura\Fixtures\AbstractFixtureTest;
use Faker\Generator;
use Faker\Factory;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class FakerListenerTest extends AbstractFixtureTest
{

    /**
     * @var Generator
     */
    private $faker;

    /**
     *
     * @var FakerListener
     */
    private $listener;

    public function setUp()
    {
        if(!class_exists('Faker\Factory')) {
            $this->markTestSkipped();
            return;
        }

        parent::setUp();
        $this->faker = Factory::create();

        $this->listener = new FakerListener($this->faker);
    }

    public function testFakerListener()
    {
        $data = array(
            'user' =>
            array(
                'data' =>
                array(
                    'user{0..2}' =>
                    array(
                        'name' => '<name()>',
                        'email' => '<email()>',
                        'random' => 'blubb{6..12}test',
                        'text' => '<sentence(3)>'
                    ),
                ),
            ),
        );


        $event = new PostFixtureLoadEvent($data);
        $this->listener->onPostFixtureLoad($event);

        $newData = $event->getData();

        $this->assertEquals(3, count($newData['user']['data']));
        $this->assertTrue(strpos($newData['user']['data']['user0']['email'], '@') !== false);
        $this->assertTrue(strpos($newData['user']['data']['user1']['email'], '@') !== false);
        $this->assertTrue(strpos($newData['user']['data']['user2']['email'], '@') !== false);
    }

}
