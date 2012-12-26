<?php

namespace DavidBadura\Fixtures\Executor;

use DavidBadura\Fixtures\Executor\Executor;
use DavidBadura\Fixtures\FixtureData;
use DavidBadura\Fixtures\FixtureCollection;
use DavidBadura\Fixtures\AbstractFixtureTest;

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
        $this->executor = new Executor();
    }

    public function testSimpleFixture()
    {
        $userFixture = $this->createUserFixture(array(
            'david' => array(
                'name' => 'David Badura',
                'email' => 'd.badura@gmx.de'
            )
        ));

        $fixtures = new FixtureCollection(array($userFixture));
        $this->executor->execute($fixtures);

        $this->assertTrue($fixtures->get('user')->hasFixtureData('david'));
        $fixtureData = $fixtures->get('user')->getFixtureData('david');
        $this->assertTrue($fixtureData->hasObject());
        $object = $fixtureData->getObject();
        $this->assertInstanceOf('DavidBadura\Fixtures\TestObjects\User', $object);
        $this->assertEquals('David Badura', $object->getName());
        $this->assertEquals('d.badura@gmx.de', $object->getEmail());
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

        $david = $fixtures->get('user')->getFixtureData('david')->getObject();
        $admin = $fixtures->get('role')->getFixtureData('admin')->getObject();

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

        $david = $fixtures->get('user')->getFixtureData('david')->getObject();
        $users = $fixtures->get('group')->getFixtureData('users')->getObject();

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
