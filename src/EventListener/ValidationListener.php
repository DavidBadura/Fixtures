<?php declare(strict_types=1);

namespace DavidBadura\Fixtures\EventListener;

use DavidBadura\Fixtures\Event\FixtureCollectionEvent;
use DavidBadura\Fixtures\Exception\ValidationException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @author David Badura <d.badura@gmx.de>
 */
class ValidationListener
{
    private $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function getValidator(): ValidatorInterface
    {
        return $this->validator;
    }

    public function onPostExecute(FixtureCollectionEvent $event): void
    {
        $collection = $event->getCollection();

        foreach ($collection as $fixture) {
            $validationEnabled = $fixture->getProperties()->get('validation_enabled', false);

            if (!$validationEnabled) {
                continue;
            }

            foreach ($fixture as $data) {
                $object = $data->getObject();

                if (!$object) {
                    continue;
                }

                $validationGroup = $fixture->getProperties()->get('validation_group', 'default');
                $violationList = $this->validator->validate($object, null, $validationGroup);

                if (count($violationList) != 0) {
                    throw new ValidationException($fixture->getName(), $data->getKey(), $violationList);
                }
            }
        }
    }
}
