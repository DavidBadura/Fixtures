<?php declare(strict_types=1);

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
class MatchLoaderTest extends \PHPUnit\Framework\TestCase
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
            ->add(new PhpLoader(), '*.php')
       ;
    }

    public function testLoadFixtures()
    {
        $user = [
            'user' =>
            [
                'properties' =>
                [
                    'class' => 'DavidBadura\\Fixtures\\TestObjects\\User',
                    'constructor' =>
                    [
                        0 => 'name',
                        1 => 'email',
                    ],
                ],
                'data' =>
                [
                    'david' =>
                    [
                        'name' => 'David Badura',
                        'email' => 'd.badura@gmx.de',
                        'group' =>
                        [
                            0 => '@group:owner',
                            1 => '@group:developer',
                        ],
                        'role' =>
                        [
                            0 => '@role:admin',
                        ],
                    ],
                    'other' =>
                    [
                        'name' => 'Somebody',
                        'email' => 'test@example.de',
                        'group' =>
                        [
                            0 => '@group:developer',
                        ],
                        'role' =>
                        [
                            0 => '@role:user',
                        ],
                    ],
                ],
            ],
        ];

        $group = [
            'group' =>
            [
                'properties' =>
                [
                    'class' => 'DavidBadura\\Fixtures\\TestObjects\\Group',
                ],
                'data' =>
                [
                    'developer' =>
                    [
                        'name' => 'Developer',
                        'leader' => '@@user:david',
                    ],
                ],
            ],
        ];

        $role = [
            'role' =>
            [
                'properties' =>
                [
                    'class' => 'DavidBadura\\Fixtures\\TestObjects\\Role',
                ],
                'data' =>
                [
                    'admin' =>
                    [
                        'name' => 'Admin',
                    ],
                    'user' =>
                    [
                        'name' => 'User',
                    ],
                ],
            ],
        ];

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
