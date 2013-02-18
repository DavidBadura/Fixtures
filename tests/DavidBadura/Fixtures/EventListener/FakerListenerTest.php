<?php

namespace DavidBadura\Fixtures\EventListener;

use DavidBadura\Fixtures\EventListener\FakerListener;
use DavidBadura\Fixtures\Event\FixtureCollectionEvent;
use DavidBadura\Fixtures\AbstractFixtureTest;
use Faker\Generator;
use Faker\Factory;
use DavidBadura\Fixtures\Fixture\FixtureCollection;

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
                        'random' => 'blubbtest',
                        'text' => '<sentence(3)>'
                    ),
                ),
            ),
        );

        $collection = FixtureCollection::create($data);


        $event = new FixtureCollectionEvent($this->createFixtureManagerMock(), $collection);
        $this->listener->onPreExecuteEvent($event);

        $fixture = $collection->get('user');
        $user0 = $fixture->get('user0')->getData();
        $user1 = $fixture->get('user1')->getData();
        $user2 = $fixture->get('user2')->getData();


        $this->assertEquals(3, count($fixture));
        $this->assertTrue(strpos($user0['email'], '@') !== false);
        $this->assertTrue(strpos($user1['email'], '@') !== false);
        $this->assertTrue(strpos($user2['email'], '@') !== false);
    }

}
