<?php

namespace DavidBadura\Fixtures\Executor;

use DavidBadura\Fixtures\Executor\Executor;
use DavidBadura\Fixtures\Fixture\FixtureCollection;
use DavidBadura\Fixtures\AbstractFixtureTest;
use DavidBadura\Fixtures\Converter\DefaultConverter;
use DavidBadura\Fixtures\Converter\ConverterRepository;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class ExecutorTest extends AbstractFixtureTest
{

    /**
     *
     * @var FixtureLoader
     */
    private $executor;



    public function setUp()
    {
        parent::setUp();
        $repository = new ConverterRepository();
        $repository->addConverter(new DefaultConverter());

        $this->executor = new Executor($repository);
    }

    public function testSimpleFixture()
    {
        $birthdate = new \DateTime();

        $userFixture = $this->createUserFixture(array(
            'david' => array(
                'name' => 'David Badura',
                'email' => 'd.badura@gmx.de',
                'birthdate' => $birthdate
            )
        ));

        $fixtures = new FixtureCollection(array($userFixture));
        $this->executor->execute($fixtures);

        $this->assertTrue($fixtures->get('user')->has('david'));
        $fixtureData = $fixtures->get('user')->get('david');
        $this->assertTrue($fixtureData->hasObject());
        $object = $fixtureData->getObject();
        $this->assertInstanceOf('DavidBadura\Fixtures\TestObjects\User', $object);
        $this->assertEquals('David Badura', $object->getName());
        $this->assertEquals('d.badura@gmx.de', $object->getEmail());
        $this->assertEquals($birthdate, $object->getBirthDate());
    }

    public function testReference()
    {
        $userFixture = $this->createUserFixture(array(
            'david' => array(
                'name' => 'David Badura',
                'email' => 'd.badura@gmx.de',
                'roles' => array('@role:admin')
            )
        ));

        $roleFixture = $this->createRoleFixture(array(
            'admin' => array(
                'name' => 'Admin'
            )
        ));

        $fixtures = new FixtureCollection(array($userFixture, $roleFixture));
        $this->executor->execute($fixtures);

        $david = $fixtures->get('user')->get('david')->getObject();
        $admin = $fixtures->get('role')->get('admin')->getObject();

        $this->assertEquals(array($admin), $david->getRoles());
    }

    public function testBiReference()
    {
        $userFixture = $this->createUserFixture(array(
            'david' => array(
                'name' => 'David Badura',
                'email' => 'd.badura@gmx.de',
                'groups' => array('@group:users')
            )
        ));

        $groupFixture = $this->createGroupFixture(array(
            'users' => array(
                'name' => 'Users',
                'leader' => '@@user:david'
            )
        ));

        $fixtures = new FixtureCollection(array($userFixture, $groupFixture));
        $this->executor->execute($fixtures);

        $david = $fixtures->get('user')->get('david')->getObject();
        $users = $fixtures->get('group')->get('users')->getObject();

        $this->assertEquals(array($users), $david->getGroups());
        $this->assertEquals($david, $users->leader);
    }

    /**
     * @expectedException DavidBadura\Fixtures\Exception\CircularReferenceException
     */
    public function testCircleReferenceException()
    {

        $userFixture = $this->createUserFixture(array(
            'david' => array(
                'name' => 'David Badura',
                'email' => 'd.badura@gmx.de',
                'groups' => array('@group:users')
            )
        ));

        $groupFixture = $this->createGroupFixture(array(
            'users' => array(
                'name' => 'Users',
                'leader' => '@user:david'
            )
        ));

        $fixtures = new FixtureCollection(array($userFixture, $groupFixture));
        $this->executor->execute($fixtures);
    }

    /**
     * @expectedException DavidBadura\Fixtures\Exception\ReferenceNotFoundException
     */
    public function testReferenceNotFoundException()
    {

        $userFixture = $this->createUserFixture(array(
            'david' => array(
                'name' => 'David Badura',
                'email' => 'd.badura@gmx.de',
                'groups' => array('@group:users')
            )
        ));

        $fixtures = new FixtureCollection(array($userFixture, ));
        $this->executor->execute($fixtures);
    }

}
