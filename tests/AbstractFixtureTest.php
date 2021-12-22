<?php declare(strict_types=1);

namespace DavidBadura\Fixtures;

use DavidBadura\Fixtures\Converter\DefaultConverter;
use DavidBadura\Fixtures\Fixture\Fixture;
use DavidBadura\Fixtures\Fixture\FixtureData;
use DavidBadura\Fixtures\Fixture\ParameterBag;
use PHPUnit\Framework\TestCase;

/**
 * @author David Badura <d.badura@gmx.de>
 */
abstract class AbstractFixtureTest extends TestCase
{
    /**
     * @var DefaultConverter
     */
    protected $converter;

    public function setUp(): void
    {
        parent::setUp();
        $this->converter = new DefaultConverter();
    }

    protected function createUserFixture($data = [])
    {
        return $this->createFixture('user', $data, [
                'class' => TestObjects\User::class,
                'constructor' => ['name', 'email'],
            ]);
    }

    protected function createGroupFixture($data = [])
    {
        return $this->createFixture('group', $data, ['class' => TestObjects\Group::class]);
    }

    protected function createRoleFixture($data = [])
    {
        return $this->createFixture('role', $data, ['class' => TestObjects\Role::class]);
    }

    protected function createFixture($name, $data = [], $properties = [])
    {
        $fixture = new Fixture($name, 'default');
        foreach ($data as $key => $value) {
            $fixture->add(new FixtureData($key, $value));
        }

        $fixture->setProperties(new ParameterBag($properties));

        return $fixture;
    }

    protected function createFixtureManagerMock()
    {
        return $this->createMock(FixtureManager\FixtureManagerInterface::class);
    }
}
