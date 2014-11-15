<?php

namespace DavidBadura\Fixtures\EventListener;

use Symfony\Component\Validator\ValidatorInterface;
use DavidBadura\Fixtures\Event\FixtureCollectionEvent;
use DavidBadura\Fixtures\Exception\ValidationException;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class ValidationListener
{

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     *
     * @param ValidatorInterface $validator
     */
    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     *
     * @return ValidatorInterface
     */
    public function getValidator()
    {
        return $this->validator;
    }

    /**
     *
     * @param FixtureCollectionEvent $event
     */
    public function onPostExecute(FixtureCollectionEvent $event)
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
                $violationList = $this->validator->validate($object, $validationGroup);

                if (count($violationList) != 0) {
                    throw new ValidationException($fixture->getName(), $data->getKey(), $violationList);
                }
            }
        }
    }
}
