<?php

namespace Cundd\CustomRest\Domain\Validator;

use Cundd\CustomRest\Domain\Model\Person;

/**
 * Class PersonValidator
 */
class PersonValidator extends \TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator
{

    /**
     * personRepository
     *
     * @var \Cundd\CustomRest\Domain\Repository\PersonRepository
     * @inject
     */
    protected $personRepository;

    /**
     * Validation of given Params
     *
     * @param $person
     * @return void
     */
    public function isValid($person)
    {
        if ($person instanceof Person) {
            if (!$this->validateCustom($person)) {
                $this->addError('validation failed!', 1472506812);
            }
        }
    }

    /**
     * Custom validation
     *
     * @param Person $person
     * @return \bool
     */
    protected function validateCustom($person)
    {
        return ($person->getFirstName() == $person->getLastName());
    }
}
