<?php

namespace DavidBadura\Fixtures;

use DavidBadura\Fixtures\FixtureFactory;
use DavidBadura\Fixtures\ConverterRepository;
use DavidBadura\Fixtures\FixtureConverter\DefaultConverter;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class FixtureFactoryTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     * @var FixtureFactory
     */
    private $factory;

    /**
     *
     * @var DefaultConverter
     */
    private $converter;

    public function setUp()
    {
        $this->converter = new DefaultConverter();

        $repo = new ConverterRepository();
        $repo->addConverter($this->converter);

        $this->factory = new FixtureFactory($repo);
    }

    public function testCreateFixtures()
    {

        $data = array(
            'user' =>
            array(
                'properties' =>
                array(
                    'class' => 'DavidBadura\\Fixtures\\Tests\\TestObjects\\User',
                    'constructor' => array('name', 'email'),
                ),
                'data' =>
                array(
                    'david' =>
                    array(
                        'name' => 'David Badura',
                        'email' => 'd.badura@gmx.de',
                        'group' => array('@group:owner', '@group:developer'),
                        'role' => array('@role:admin'),
                    ),
                    'other' =>
                    array(
                        'name' => 'Somebody',
                        'email' => 'test@example.de',
                        'group' => array('@group:developer'),
                        'role' => array('@role:user'),
                    ),
                ),
            ),
            'group' =>
            array(
                'properties' =>
                array(
                    'class' => 'DavidBadura\\Fixtures\\Tests\\TestObjects\\Group',
                ),
                'tags' => array('install', 'test'),
                'data' =>
                array(
                    'developer' =>
                    array(
                        'name' => 'Developer',
                        'leader' => '@@user:david',
                    ),
                ),
            ),
            'role' =>
            array(
                'properties' =>
                array(
                    'class' => 'DavidBadura\\Fixtures\\Tests\\TestObjects\\Role',
                ),
                'tags' => array('test'),
                'data' =>
                array(
                    'admin' =>
                    array(
                        'name' => 'Admin',
                    ),
                    'user' =>
                    array(
                        'name' => 'User',
                    ),
                ),
            ),
        );

        $fixtures = $this->factory->createFixtures($data);

        $this->assertEquals(3, count($fixtures));

        $this->assertEquals('user', $fixtures->get('user')->getName());
        $this->assertEquals('group', $fixtures->get('group')->getName());
        $this->assertEquals('role', $fixtures->get('role')->getName());

        $this->assertEquals($this->converter, $fixtures->get('user')->getConverter());
        $this->assertEquals($this->converter, $fixtures->get('group')->getConverter());
        $this->assertEquals($this->converter, $fixtures->get('role')->getConverter());

        $this->assertEquals(array(), $fixtures->get('user')->getTags());
        $this->assertEquals(array('install', 'test'), $fixtures->get('group')->getTags());
        $this->assertEquals(array('test'), $fixtures->get('role')->getTags());

        $this->assertEquals(array('class' => 'DavidBadura\\Fixtures\\Tests\\TestObjects\\User',
            'constructor' => array('name', 'email')),
            $fixtures->get('user')->getProperties());

        $this->assertEquals(array('class' => 'DavidBadura\\Fixtures\\Tests\\TestObjects\\Group'),
            $fixtures->get('group')->getProperties());

        $this->assertEquals(array('class' => 'DavidBadura\\Fixtures\\Tests\\TestObjects\\Role'),
            $fixtures->get('role')->getProperties());

    }

}
