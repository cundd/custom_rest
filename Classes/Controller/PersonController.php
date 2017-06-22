<?php

namespace Cundd\CustomRest\Controller;

/**
 * Person Controller
 *
 * @package custom_rest
 */

use \Cundd\CustomRest\Domain\Model\Person;
use \TYPO3\CMS\Extbase\Property\PropertyMappingConfigurationBuilder;
use \TYPO3\CMS\Extbase\Property\TypeConverter\PersistentObjectConverter;
use \TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class PersonController extends ActionController
{
    /**
     * @var \TYPO3\CMS\Extbase\Mvc\View\JsonView
     */
    protected $view;

    /**
     * @var string
     */
    protected $defaultViewObjectName = \TYPO3\CMS\Extbase\Mvc\View\JsonView::class;

    /**
     * personRepository
     *
     * @var \Cundd\CustomRest\Domain\Repository\PersonRepository
     * @inject
     */
    protected $personRepository;


    /* ----------------- GET -------------*/

    /**
     * action list
     *
     * @return void
     */
    public function listAction()
    {
        $this->view->assign('value', $this->personRepository->findAll());
    }

    /**
     * action show
     *
     * @param integer $uid
     * @return void
     */
    public function showAction($uid)
    {
        $this->view->assign('value', $this->personRepository->findByUid($uid));
    }

    /**
     * action firstName
     *
     * @param string $firstName
     * @return void
     */
    public function firstNameAction($firstName)
    {
        $this->view->assign('value', $this->personRepository->findByFirstName($firstName));
    }

    /**
     * action lastName
     *
     * @param string $lastName
     * @return void
     */
    public function lastNameAction($lastName)
    {
        $this->view->assign('value', $this->personRepository->findByLastName($lastName));
    }

    /**
     * action birthday
     *
     * @param string $date
     * @return void
     */
    public function birthdayAction($date)
    {
        $this->view->assign('value', $this->personRepository->findByBirthday($date));
    }


    /* ----------------- POST -------------*/

    /**
     * initialize action create
     */
    public function initializeCreateAction()
    {
        // if the request does not come from a fluid form:
        // * the properties which are allowed to map must be set manually
        if (!$this->request->getInternalArgument('__trustedProperties')) {
            $this->addPropertyMappingConfiguration();
        }
    }

    /**
     * action create
     *
     * @param \Cundd\CustomRest\Domain\Model\Person $person
     */
    public function createAction(Person $person)
    {
        $this->personRepository->add($person);

        $this->view->assign('value', ['success' => 1]);
    }

    /*-----------------------------------------------------------------*/

    /**
     * error action
     */
    protected function errorAction()
    {
        $flattenedValidationErrors = $this->arguments->getValidationResults()->getFlattenedErrors();

        $response = [
            'success' => 0,
            'errors'  => $flattenedValidationErrors['person'],
        ];

        $this->view->assign('value', $response);
    }


    /**
     * addPropertyMappingConfiguration
     */
    protected function addPropertyMappingConfiguration()
    {
        if ($this->request->hasArgument('person')) {
            $propertyMappingConfiguration = (new PropertyMappingConfigurationBuilder())->build(
                'TYPO3\\CMS\\Extbase\\Mvc\\Controller\\MvcPropertyMappingConfiguration'
            );
            $propertyMappingConfiguration->setTypeConverterOption(
                PersistentObjectConverter::class,
                PersistentObjectConverter::CONFIGURATION_CREATION_ALLOWED,
                true
            );

            foreach ($this->request->getArgument('person') as $propertyName => $value) {
                $propertyMappingConfiguration->allowProperties($propertyName);
            }

            $this->arguments->getArgument('person')->injectPropertyMappingConfiguration($propertyMappingConfiguration);
        }
    }

}
