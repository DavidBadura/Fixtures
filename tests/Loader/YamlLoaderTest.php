<?php declare(strict_types=1);

namespace DavidBadura\Fixtures\Loader;

use DavidBadura\Fixtures\Fixture\FixtureCollection;
use PHPUnit\Framework\TestCase;
use DavidBadura\Fixtures\TestObjects\User;

/**
 * @author David Badura <d.badura@gmx.de>
 */
class YamlLoaderTest extends TestCase
{

    /**
     * @var YamlLoader
     */
    private $loader;

    public function setUp(): void
    {
        $this->loader = new YamlLoader();
    }

    public function testLoadFixture()
    {
        $expects = [
            'user' =>
                [
                    'properties' =>
                        [
                            'class' => User::class,
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

        $collection = FixtureCollection::create($expects);

        $data = $this->loader->load(__DIR__.'/../TestResources/fixtures/user.yml');

        $this->assertEquals($collection, $data);
    }
}
