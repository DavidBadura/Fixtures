<?php

namespace DavidBadura\Fixtures;

use DavidBadura\Fixtures\FixtureConverter\DefaultConverter;
use DavidBadura\Fixtures\Fixture;
use DavidBadura\Fixtures\FixtureData;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
abstract class AbstractFixtureTest extends \PHPUnit_Framework_TestCase
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

    protected function createUserFixture($data = array())
    {
        return $this->createFixture('user', $data, array(
                'class' => 'DavidBadura\Fixtures\TestObjects\User',
                'constructor' => array('name', 'email')
            ));
    }

    protected function createGroupFixture($data = array())
    {
        return $this->createFixture('group', $data, array('class' => 'DavidBadura\Fixtures\TestObjects\Group'));
    }

    protected function createRoleFixture($data = array())
    {
        return $this->createFixture('role', $data, array('class' => 'DavidBadura\Fixtures\TestObjects\Role'));
    }

    protected function createFixture($name, $data = array(), $properties = array())
    {
        $fixture = new Fixture($name, $this->converter);
        foreach ($data as $key => $value) {
            $fixture->addFixtureData(new FixtureData($key, $value));
        }

        $fixture->setProperties($properties);

        return $fixture;
    }

}
