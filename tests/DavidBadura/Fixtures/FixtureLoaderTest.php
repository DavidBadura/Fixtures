<?php

namespace DavidBadura\Fixtures;

use DavidBadura\Fixtures\FixtureLoader;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class FixtureLoaderTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     * @var FixtureLoader
     */
    private $loader;

    public function setUp()
    {
        $this->loader = new FixtureLoader();
    }

    public function testLoadFixturesByPath()
    {

        $expects = array(
            'user' =>
            array(
                'properties' =>
                array(
                    'class' => 'DavidBadura\\Fixtures\\TestObjects\\User',
                    'constructor' =>
                    array(
                        0 => 'name',
                        1 => 'email',
                    ),
                ),
                'data' =>
                array(
                    'david' =>
                    array(
                        'name' => 'David Badura',
                        'email' => 'd.badura@gmx.de',
                        'group' =>
                        array(
                            0 => '@group:owner',
                            1 => '@group:developer',
                        ),
                        'role' =>
                        array(
                            0 => '@role:admin',
                        ),
                    ),
                    'other' =>
                    array(
                        'name' => 'Somebody',
                        'email' => 'test@example.de',
                        'group' =>
                        array(
                            0 => '@group:developer',
                        ),
                        'role' =>
                        array(
                            0 => '@role:user',
                        ),
                    ),
                ),
            ),
            'group' =>
            array(
                'properties' =>
                array(
                    'class' => 'DavidBadura\\Fixtures\\TestObjects\\Group',
                ),
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
                    'class' => 'DavidBadura\\Fixtures\\TestObjects\\Role',
                ),
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

        $logger = $this->getMock('DavidBadura\Fixtures\Logger\Logger');
        $logger->expects($this->exactly(3))->method('log');

        $data = $this->loader->loadFixtures(__DIR__ . '/TestResources/fixtures', $logger);
        $this->assertEquals($expects, $data);
    }

}
