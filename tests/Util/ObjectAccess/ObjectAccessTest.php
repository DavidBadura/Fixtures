<?php declare(strict_types=1);

namespace DavidBadura\Fixtures\Util\ObjectAccess;

use DavidBadura\Fixtures\Util\ObjectAccess\ObjectAccess;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class ObjectAccessTest extends \PHPUnit\Framework\TestCase
{
    public function testStdClass()
    {
        $object = new \stdClass();
        $access = new ObjectAccess($object);

        $access->writeProperty('test', 123);
        $this->assertEquals(123, $object->test);
    }

    public function testPublicProperty()
    {
        $object = new AccessObject();
        $access = new ObjectAccess($object);

        $access->writeProperty('publicTestProperty', 'test123');
        $this->assertEquals('test123', $object->publicTestProperty);
    }

    public function testProtectdProperty()
    {
        $this->expectException('DavidBadura\Fixtures\Util\ObjectAccess\ObjectAccessException');

        $object = new AccessObject();
        $access = new ObjectAccess($object);

        $access->writeProperty('protectedTestProperty', 'test123');
    }

    public function testPublicSetterMethod()
    {
        $object = new AccessObject();
        $access = new ObjectAccess($object);

        $access->writeProperty('publicTestMethod', 'test123');
        $this->assertEquals('test123', $object->setPublicTestMethodVar);
    }

    public function testProtectdSetterMethod()
    {
        $this->expectException('DavidBadura\Fixtures\Util\ObjectAccess\ObjectAccessException');

        $object = new AccessObject();
        $access = new ObjectAccess($object);

        $access->writeProperty('protectedTestMethod', 'test123');
    }

    public function testPublicAdderMethod()
    {
        $object = new AccessObject();
        $access = new ObjectAccess($object);

        $value = ['test123', 123, 'blubb'];

        $access->writeProperty('publicTestMethodArray', $value);
        $this->assertEquals($value, $object->addPublicTestMethodArrayVar);
    }

    public function testPublicAdderMethodSingular()
    {
        $object = new AccessObject();
        $access = new ObjectAccess($object);

        $value = ['test123', 123, 'blubb'];

        $access->writeProperty('publicTestMethodArrays', $value);
        $this->assertEquals($value, $object->addPublicTestMethodArrayVar);
    }

    public function testProtectedAdderMethod()
    {
        $this->expectException('DavidBadura\Fixtures\Util\ObjectAccess\ObjectAccessException');

        $object = new AccessObject();
        $access = new ObjectAccess($object);

        $value = ['test123', 123, 'blubb'];

        $access->writeProperty('protectedTestMethodArray', $value);
    }

    public function testArrayCollection()
    {
        $object = new AccessObject();
        $access = new ObjectAccess($object);

        $value = ['test123', 123, 'blubb'];

        $access->writeProperty('publicArrayCollection', $value);
        $this->assertEquals($value, $object->arrayCollection->toArray());
    }

    public function testMagicSetter()
    {
        $object = new MagicAccessObject();
        $access = new ObjectAccess($object);

        $access->writeProperty('testProperty', 'test123');
        $this->assertEquals('test123', $object->testProperty);
    }

    public function testNotExsistProperty()
    {
        $this->expectException('DavidBadura\Fixtures\Util\ObjectAccess\ObjectAccessException');

        $object = new AccessObject();
        $access = new ObjectAccess($object);

        $access->writeProperty('asd', 'test123');
    }

    public function testSetDateTimeMethod()
    {
        $object = new AccessObject();
        $access = new ObjectAccess($object);

        $access->writeProperty('dateTimeMethod', 'now');

        $this->assertInstanceOf('\DateTime', $object->setDateTimeMethod);
    }

    public function testAddDateTimeMethod()
    {
        $object = new AccessObject();
        $access = new ObjectAccess($object);

        $access->writeProperty('multiDateTimeMethod', ['now']);

        $this->assertInstanceOf('\DateTime', $object->addDateTimeMethod[0]);
    }

    public function testInvalidSetDateTimeMethod()
    {
        $this->expectException('DavidBadura\Fixtures\Util\ObjectAccess\ObjectAccessException');

        $object = new AccessObject();
        $access = new ObjectAccess($object);

        $access->writeProperty('dateTimeMethod', 'not valid');

        $this->assertInstanceOf('\DateTime', $object->setDateTimeMethod);
    }
}
