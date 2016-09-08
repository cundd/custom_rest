<?php

namespace Cundd\CustomRest\Controller;

    /***************************************************************
     *  Copyright notice
     *
     *  (c) 2016 Ben Walch <walch.ben@gmx.at>
     *
     *  All rights reserved
     *
     *  This script is part of the TYPO3 project. The TYPO3 project is
     *  free software; you can redistribute it and/or modify
     *  it under the terms of the GNU General Public License as published by
     *  the Free Software Foundation; either version 3 of the License, or
     *  (at your option) any later version.
     *
     *  The GNU General Public License can be found at
     *  http://www.gnu.org/copyleft/gpl.html.
     *
     *  This script is distributed in the hope that it will be useful,
     *  but WITHOUT ANY WARRANTY; without even the implied warranty of
     *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
     *  GNU General Public License for more details.
     *
     *  This copyright notice MUST APPEAR in all copies of the script!
     ***************************************************************/


/**
 * Example Controller
 *
 * @package custom_rest
 */

use \Cundd\CustomRest\Domain\Model\Person;
use \TYPO3\CMS\Extbase\Property\PropertyMappingConfigurationBuilder;
use \TYPO3\CMS\Extbase\Property\TypeConverter\PersistentObjectConverter;

class ExampleController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
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


    /**
     * error action
     */
    protected function errorAction()
    {
        $flattenedValidationErrors = $this->arguments->getValidationResults()->getFlattenedErrors();

        $response = [
            'success' => 0,
            'errors' => $flattenedValidationErrors['person']
        ];

        $this->view->assign('value', $response);
    }

    /**
     * addPropertyMappingConfiguration
     */
    protected function addPropertyMappingConfiguration()
    {
        if ($this->request->hasArgument('person')) {
            $propertyMappingConfiguration = (new PropertyMappingConfigurationBuilder())->build('TYPO3\\CMS\\Extbase\\Mvc\\Controller\\MvcPropertyMappingConfiguration');
            $propertyMappingConfiguration->setTypeConverterOption(PersistentObjectConverter::class, PersistentObjectConverter::CONFIGURATION_CREATION_ALLOWED, TRUE);

            foreach ($this->request->getArgument('person') as $propertyName => $value) {
                $propertyMappingConfiguration->allowProperties($propertyName);
            }
            $this->arguments->getArgument('person')->injectPropertyMappingConfiguration($propertyMappingConfiguration);
        }
    }
}
