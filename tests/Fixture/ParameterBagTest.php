<?php

namespace DavidBadura\Fixtures\Fixture;

use DavidBadura\Fixtures\Fixture\ParameterBag;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class ParameterBagTest extends \PHPUnit_Framework_TestCase
{

    public function testMainFunctionality()
    {
        $bag = new ParameterBag(array(
            'foo' => 'bar',
            'test' => 123
        ));

        $this->assertEquals('bar', $bag->get('foo'));
        $this->assertEquals(123, $bag->get('test'));
        $this->assertEquals('bar', $bag['foo']);
        $this->assertEquals(123, $bag['test']);

        $this->assertEquals(false, isset($bag['xxx']));

        $bag['xxx'] = 'yyy';
        $this->assertEquals('yyy', $bag->get('xxx'));
        $this->assertEquals(true, isset($bag['xxx']));

        unset($bag['xxx']);
        $this->assertEquals(false, $bag->has('xxx'));
        $this->assertEquals('bar', $bag->get('foo'));
        $this->assertEquals(123, $bag->get('test'));
    }

}
