<?php declare(strict_types=1);

namespace DavidBadura\Fixtures\EventListener;

use DavidBadura\Fixtures\EventListener\ValidationListener;
use DavidBadura\Fixtures\Event\FixtureCollectionEvent;
use Symfony\Component\Validator\ValidatorInterface;
use DavidBadura\Fixtures\Fixture\FixtureCollection;
use DavidBadura\Fixtures\AbstractFixtureTest;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class ValidationListenerTest extends AbstractFixtureTest
{

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     *
     * @var PersistListener
     */
    private $listener;

    public function setUp()
    {
        parent::setUp();
        $this->validator = $this->createMock('Symfony\Component\Validator\ValidatorInterface');
        $this->validator->expects($this->never())->method('validate')->will($this->returnValue([]));

        $this->listener = new ValidationListener($this->validator);
    }

    public function testValidationListener()
    {
        $fixtures = new FixtureCollection([
            $this->createFixture('test1', ['key1' => 'data1']),
            $this->createFixture('test2', ['key2' => 'data2']),
        ]);

        $event = new FixtureCollectionEvent($this->createFixtureManagerMock(), $fixtures);
        $this->listener->onPostExecute($event);
    }
}
