<?php

namespace DavidBadura\Fixtures\FixtureManager;

use DavidBadura\Fixtures\ServiceProvider\ServiceProviderInterface;
use DavidBadura\Fixtures\Loader\LoaderInterface;
use DavidBadura\Fixtures\Persister\PersisterInterface;
use DavidBadura\Fixtures\Executor\ExecutorInterface;
use DavidBadura\Fixtures\Fixture\FixtureCollection;
use DavidBadura\Fixtures\AbstractFixtureTest;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class FixtureManagerTest extends AbstractFixtureTest
{

    /**
     *
     * @var FixtureManagerPublicMethods
     */
    private $fixtureManager;

    /**
     *
     * @var LoaderInterface
     */
    private $loader;

    /**
     *
     * @var ExecutorInterface
     */
    private $executor;

    /**
     * @var PersisterInterface
     */
    private $persister;

    /**
     *
     * @var ServiceProviderInterface
     */
    private $provider;

    /**
     *
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;


    public function setUp()
    {
        parent::setUp();

        $this->loader = $this->getMock('DavidBadura\Fixtures\Loader\LoaderInterface');
        $this->loader->expects($this->any())->method('load')->will($this->returnValue(new FixtureCollection()));

        $this->executor = $this->getMock('DavidBadura\Fixtures\Executor\ExecutorInterface');
        $this->executor->expects($this->any())->method('execute');

        $this->persister = $this->getMock('DavidBadura\Fixtures\Persister\PersisterInterface');
        $this->persister->expects($this->any())->method('addObject');
        $this->persister->expects($this->any())->method('save');

        $this->provider = $this->getMock('DavidBadura\Fixtures\ServiceProvider\ServiceProviderInterface');
        $this->provider->expects($this->any())->method('get');

        $this->eventDispatcher = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $this->eventDispatcher->expects($this->any())->method('dispatch');

        $this->fixtureManager = new FixtureManagerPublicMethods($this->loader, $this->executor,
            $this->persister, $this->provider, $this->eventDispatcher);
    }

    public function testFixtureManager()
    {
        $this->loader->expects($this->once())->method('load')->will($this->returnValue(new FixtureCollection()));
        $this->executor->expects($this->once())->method('execute');
        $this->persister->expects($this->once())->method('save');
        $this->eventDispatcher->expects($this->exactly(5))->method('dispatch');

        $this->fixtureManager->load(null);
    }

    public function testFilterByTags()
    {
        $fixture1 = $this->createFixture('test1', array(), array(
            'tags' => array('test', 'install')
        ));

        $fixture2 = $this->createFixture('test2', array(), array(
            'tags' => array('test')
        ));

        $fixture3 = $this->createFixture('test3', array(), array(
            'tags' => array('install')
        ));

        $fixture4 = $this->createFixture('test4');

        $collection = new FixtureCollection(array($fixture1, $fixture2, $fixture3, $fixture4));
        $this->fixtureManager->publicFilterByTags($collection, array());
        $this->assertEquals(new FixtureCollection(array($fixture1, $fixture2, $fixture3, $fixture4)), $collection);

        $collection = new FixtureCollection(array($fixture1, $fixture2, $fixture3, $fixture4));
        $this->fixtureManager->publicFilterByTags($collection, array('install'));
        $this->assertEquals(new FixtureCollection(array($fixture1, $fixture3)), $collection);

        $collection = new FixtureCollection(array($fixture1, $fixture2, $fixture3, $fixture4));
        $this->fixtureManager->publicFilterByTags($collection, array('install', 'test'));
        $this->assertEquals(new FixtureCollection(array($fixture1, $fixture2, $fixture3)), $collection);
    }

    public function testServiceProvider()
    {
        $serviceProvicer = new \DavidBadura\Fixtures\ServiceProvider\ServiceProvider();
        $fixtureManager = new FixtureManagerPublicMethods($this->loader, $this->executor, $this->persister, $serviceProvicer, $this->eventDispatcher);

        $faker =  \Faker\Factory::create();
        $fixtureManager->addService('faker', $faker);

        $data = array(
            'user' =>
            array(
                'data' =>
                array(
                    'user{0..2}' =>
                    array(
                        'name' => '<faker::name()>',
                        'email' => '<faker::email()>',
                        'random' => 'blubbtest',
                        'text' => '<faker::sentence(3)>'
                    ),
                ),
            ),
        );

        $collection = FixtureCollection::create($data);

        $fixtureManager->publicReplaceMultiPlaceholder($collection);
        $fixtureManager->publicReplaceServicePlaceholder($collection);

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
