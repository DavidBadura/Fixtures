<?php declare(strict_types=1);

namespace DavidBadura\Fixtures\Converter;

use DavidBadura\Fixtures\Fixture\Fixture;
use DavidBadura\Fixtures\Fixture\FixtureData;
use DavidBadura\Fixtures\Fixture\ParameterBag;
use DavidBadura\Fixtures\TestObjects\Post;
use DavidBadura\Fixtures\TestObjects\User;
use PHPUnit\Framework\TestCase;

/**
 * @author David Badura <d.badura@gmx.de>
 */
class DefaultConverterTest extends TestCase
{
    /**
     * @var DefaultConverter
     */
    protected $converter;

    public function setUp(): void
    {
        $this->converter = new DefaultConverter();
    }

    public function testDefaultConverterCreateObject()
    {
        $fixture = new Fixture('test');
        $fixture->setProperties(new ParameterBag([
            'class' => User::class,
            'constructor' => ['name', 'email'],
        ]));

        $data = new FixtureData('test', [
            'name' => 'test_name',
            'email' => 'test_email',
            'groups' => ['xyz', 'abc'],
        ]);

        $data->setFixture($fixture);

        $object = $this->converter->createObject($data);

        $this->assertInstanceOf(User::class, $object);
        $this->assertEquals('test_name', $object->getName());
        $this->assertEquals('test_email', $object->getEmail());

        $this->converter->finalizeObject($object, $data);

        $this->assertEquals(['xyz', 'abc'], $object->getGroups());
    }

    public function testDefaultConverterCreateObject_UniqueId()
    {
        $fixture = new Fixture('test');
        $fixture->setProperties(new ParameterBag([
            'class' => User::class,
            'constructor' => ['name', 'email'],
        ]));

        $data = new FixtureData('test', [
            'name' => 'test_name {unique_id}',
            'email' => 'test_email',
            'description' => 'test_description {unique_id}',
        ]);

        $data->setFixture($fixture);

        $object = $this->converter->createObject($data);

        $this->assertInstanceOf(User::class, $object);
        $this->assertMatchesRegularExpression('/test_name .{13}/', $object->getName());
        $this->assertEquals('test_email', $object->getEmail());

        $this->converter->finalizeObject($object, $data);

        $this->assertMatchesRegularExpression('/test_description .{13}/', $object->getDescription());
    }

    public function testDateTimeConstructor()
    {
        $fixture = new Fixture('test');
        $fixture->setProperties(new ParameterBag([
            'class' => Post::class,
            'constructor' => ['name', 'date'],
        ]));

        $data = new FixtureData('test', [
            'name' => 'test_name',
            'date' => 'now',
        ]);

        $data->setFixture($fixture);

        $object = $this->converter->createObject($data);

        $this->assertInstanceOf(Post::class, $object);
        $this->assertEquals('test_name', $object->getName());
        $this->assertInstanceOf('DateTime', $object->getDate());
    }

    public function testnullableCostructorArgument()
    {
        $fixture = new Fixture('test');
        $fixture->setProperties(new ParameterBag([
            'class' => User::class,
            'constructor' => ['name', 'email'],
        ]));

        $data = new FixtureData('test', [
            'name' => 'test_name',
            'email' => null,
        ]);

        $data->setFixture($fixture);

        $object = $this->converter->createObject($data);

        $this->assertInstanceOf(User::class, $object);
        $this->assertEquals('test_name', $object->getName());
        $this->assertNull($object->getEmail());
    }
}
