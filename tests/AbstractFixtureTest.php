<?php declare(strict_types=1);

namespace DavidBadura\Fixtures;

use DavidBadura\Fixtures\Converter\DefaultConverter;
use DavidBadura\Fixtures\Fixture\Fixture;
use DavidBadura\Fixtures\Fixture\FixtureData;
use DavidBadura\Fixtures\Fixture\ParameterBag;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
abstract class AbstractFixtureTest extends \PHPUnit\Framework\TestCase
{

    /**
     *
     * @var DefaultConverter
     */
    protected $converter;

    public function setUp()
    {
        parent::setUp();
        $this->converter = new DefaultConverter();
    }

    protected function createUserFixture($data = [])
    {
        return $this->createFixture('user', $data, [
                'class' => 'DavidBadura\Fixtures\TestObjects\User',
                'constructor' => ['name', 'email'],
            ]);
    }

    protected function createGroupFixture($data = [])
    {
        return $this->createFixture('group', $data, ['class' => 'DavidBadura\Fixtures\TestObjects\Group']);
    }

    protected function createRoleFixture($data = [])
    {
        return $this->createFixture('role', $data, ['class' => 'DavidBadura\Fixtures\TestObjects\Role']);
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

    /**
     * @return FixtureManager\FixtureManagerInterface
     */
    protected function createFixtureManagerMock()
    {
        return $this->createMock('DavidBadura\Fixtures\FixtureManager\FixtureManagerInterface');
    }
}
