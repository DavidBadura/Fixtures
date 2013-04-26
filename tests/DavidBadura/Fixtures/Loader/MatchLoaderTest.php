<?php

namespace DavidBadura\Fixtures\Loader;

use DavidBadura\Fixtures\Loader\MatchLoader;
use DavidBadura\Fixtures\Loader\YamlLoader;
use DavidBadura\Fixtures\Loader\JsonLoader;
use DavidBadura\Fixtures\Loader\ArrayLoader;
use DavidBadura\Fixtures\Fixture\FixtureCollection;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class MatchLoaderTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     * @var FixtureLoader
     */
    private $loader;

    public function setUp()
    {
        $this->loader = new MatchLoader();
        $this->loader
            ->add(new JsonLoader(), '*.json')
            ->add(new YamlLoader(), '*.yml')
            ->add(new ArrayLoader(), '*.php')
       ;
    }

    public function testLoadFixtures()
    {
        $user = array(
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
            )
        );

        $group = array(
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
            )
        );

        $role = array(
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

        $this->assertEquals(
            FixtureCollection::create($user),
            $this->loader->load(__DIR__ . '/../TestResources/chainFixtures/user.yml')
        );

        $this->assertEquals(
            FixtureCollection::create($group),
            $this->loader->load(__DIR__ . '/../TestResources/chainFixtures/groups.json')
        );

        $this->assertEquals(
            FixtureCollection::create($role),
            $this->loader->load(__DIR__ . '/../TestResources/chainFixtures/roles.php')
        );
    }

}
