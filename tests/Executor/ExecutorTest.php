<?php declare(strict_types=1);

namespace DavidBadura\Fixtures\Executor;

use DavidBadura\Fixtures\AbstractFixtureTest;
use DavidBadura\Fixtures\Converter\ConverterRepository;
use DavidBadura\Fixtures\Converter\DefaultConverter;
use DavidBadura\Fixtures\Exception\CircularReferenceException;
use DavidBadura\Fixtures\Exception\ReferenceNotFoundException;
use DavidBadura\Fixtures\Fixture\FixtureCollection;
use DavidBadura\Fixtures\TestObjects\User;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class ExecutorTest extends AbstractFixtureTest
{
    /**
     * @var Executor
     */
    private $executor;

    public function setUp(): void
    {
        parent::setUp();
        $repository = new ConverterRepository();
        $repository->addConverter(new DefaultConverter());

        $this->executor = new Executor($repository);
    }

    public function testSimpleFixture()
    {
        $birthdate = new \DateTime();

        $userFixture = $this->createUserFixture([
            'david' => [
                'name' => 'David Badura',
                'email' => 'd.badura@gmx.de',
                'birthdate' => $birthdate,
            ],
        ]);

        $fixtures = new FixtureCollection([$userFixture]);
        $this->executor->execute($fixtures);

        $this->assertTrue($fixtures->get('user')->has('david'));
        $fixtureData = $fixtures->get('user')->get('david');
        $this->assertTrue($fixtureData->hasObject());
        $object = $fixtureData->getObject();
        $this->assertInstanceOf(User::class, $object);
        $this->assertEquals('David Badura', $object->getName());
        $this->assertEquals('d.badura@gmx.de', $object->getEmail());
        $this->assertEquals($birthdate, $object->getBirthDate());
    }

    public function testReference()
    {
        $userFixture = $this->createUserFixture([
            'david' => [
                'name' => 'David Badura',
                'email' => 'd.badura@gmx.de',
                'roles' => ['@role:admin'],
            ],
        ]);

        $roleFixture = $this->createRoleFixture([
            'admin' => [
                'name' => 'Admin',
            ],
        ]);

        $fixtures = new FixtureCollection([$userFixture, $roleFixture]);
        $this->executor->execute($fixtures);

        $david = $fixtures->get('user')->get('david')->getObject();
        $admin = $fixtures->get('role')->get('admin')->getObject();

        $this->assertEquals([$admin], $david->getRoles());
    }

    public function testBiReference()
    {
        $userFixture = $this->createUserFixture([
            'david' => [
                'name' => 'David Badura',
                'email' => 'd.badura@gmx.de',
                'groups' => ['@group:users'],
            ],
        ]);

        $groupFixture = $this->createGroupFixture([
            'users' => [
                'name' => 'Users',
                'leader' => '@@user:david',
            ],
        ]);

        $fixtures = new FixtureCollection([$userFixture, $groupFixture]);
        $this->executor->execute($fixtures);

        $david = $fixtures->get('user')->get('david')->getObject();
        $users = $fixtures->get('group')->get('users')->getObject();

        $this->assertEquals([$users], $david->getGroups());
        $this->assertEquals($david, $users->leader);
    }

    public function testCircleReferenceException()
    {
        $this->expectException(CircularReferenceException::class);


        $userFixture = $this->createUserFixture([
            'david' => [
                'name' => 'David Badura',
                'email' => 'd.badura@gmx.de',
                'groups' => ['@group:users'],
            ],
        ]);

        $groupFixture = $this->createGroupFixture([
            'users' => [
                'name' => 'Users',
                'leader' => '@user:david',
            ],
        ]);

        $fixtures = new FixtureCollection([$userFixture, $groupFixture]);
        $this->executor->execute($fixtures);
    }

    public function testReferenceNotFoundException()
    {
        $this->expectException(ReferenceNotFoundException::class);


        $userFixture = $this->createUserFixture([
            'david' => [
                'name' => 'David Badura',
                'email' => 'd.badura@gmx.de',
                'groups' => ['@group:users'],
            ],
        ]);

        $fixtures = new FixtureCollection([$userFixture,]);
        $this->executor->execute($fixtures);
    }
}
