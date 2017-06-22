<?php
/*
 *  Copyright notice
 *
 *  (c) 2014 Daniel Corn <info@cundd.net>, cundd
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
 */

/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 01.04.14
 * Time: 21:55
 */

namespace Cundd\CustomRest\Rest;


use Cundd\Rest\Handler\HandlerInterface;
use Cundd\Rest\Http\RestRequestInterface;
use Cundd\Rest\Request;
use Cundd\Rest\Router\Route;
use Cundd\Rest\Router\RouterInterface;

/**
 * Example handler
 *
 * @package Cundd\Rest\Handler
 */
class Handler implements HandlerInterface
{
    /**
     * @var \Cundd\Rest\ObjectManagerInterface
     * @inject
     */
    protected $objectManager;

    /**
     * @var \Cundd\Rest\ResponseFactoryInterface
     * @inject
     */
    protected $responseFactory;

    /**
     * @var \Cundd\CustomRest\Rest\Helper
     * @inject
     */
    protected $helper;


    /**
     * @inheritDoc
     */
    public function configureRoutes(RouterInterface $router, RestRequestInterface $request)
    {
        /*------------------------------------------------------
         * Simple callback functions
         *-----------------------------------------------------*/

        /*
         * These customhandler example routes return hardcoded values. They do not call any
         * extbase controller functions. (For that, see PersonController and Routes below.)
         */

        # curl -X GET http://localhost:8888/rest/customhandler
        $router->add(
            Route::get(
                $request->getResourceType(),
                function (RestRequestInterface $request) {
                    return [
                        'path'         => $request->getPath(),
                        'uri'          => (string)$request->getUri(),
                        'resourceType' => (string)$request->getResourceType(),
                    ];
                }
            )
        );

        # curl -X GET http://localhost:8888/rest/customhandler/subpath
        $router->add(
            Route::get(
                $request->getResourceType() . '/subpath',
                function (RestRequestInterface $request) {
                    return [
                        'path'         => $request->getPath(),
                        'uri'          => (string)$request->getUri(),
                        'resourceType' => (string)$request->getResourceType(),
                    ];
                }
            )
        );

        # curl -X POST -d '{"username":"johndoe","password":"123456"}' http://localhost:8888/rest/customhandler/subpath
        $router->add(
            Route::post(
                $request->getResourceType() . '/subpath',
                function (RestRequestInterface $request) {
                    return [
                        'path'         => $request->getPath(),
                        'uri'          => (string)$request->getUri(),
                        'resourceType' => (string)$request->getResourceType(),
                        'data'         => $request->getSentData(),
                    ];
                }
            )
        );

        # curl -X GET http://localhost:8888/rest/customhandler/parameter/slug
        $router->add(
            Route::get(
                $request->getResourceType() . '/parameter/{slug}',
                function (RestRequestInterface $request, $slug) {
                    return [
                        'slug'         => $slug,
                        'path'         => $request->getPath(),
                        'uri'          => (string)$request->getUri(),
                        'resourceType' => (string)$request->getResourceType(),
                    ];
                }
            )
        );

        # curl -X GET http://localhost:8888/rest/customhandler/12
        $router->add(
            Route::get(
                $request->getResourceType() . '/{int}',
                function (RestRequestInterface $request, $parameter) {
                    return [
                        'value'         => $parameter,
                        'parameterType' => gettype($parameter),
                        'path'          => $request->getPath(),
                        'uri'           => (string)$request->getUri(),
                        'resourceType'  => (string)$request->getResourceType(),
                    ];
                }
            )
        );

        # curl -X GET http://localhost:8888/rest/customhandler/decimal/10.8
        $router->add(
            Route::get(
                $request->getResourceType() . '/decimal/{float}',
                function (RestRequestInterface $request, $parameter) {
                    return [
                        'value'         => $parameter,
                        'parameterType' => gettype($parameter),
                        'path'          => $request->getPath(),
                        'uri'           => (string)$request->getUri(),
                        'resourceType'  => (string)$request->getResourceType(),
                    ];
                }
            )
        );

        # curl -X GET http://localhost:8888/rest/customhandler/bool/yes
        # curl -X GET http://localhost:8888/rest/customhandler/bool/no
        $router->add(
            Route::get(
                $request->getResourceType() . '/bool/{bool}',
                function (RestRequestInterface $request, $parameter) {
                    return [
                        'value'         => $parameter,
                        'parameterType' => gettype($parameter),
                        'path'          => $request->getPath(),
                        'uri'           => (string)$request->getUri(),
                        'resourceType'  => (string)$request->getResourceType(),
                    ];
                }
            )
        );


        /*------------------------------------------------------
         * Sample Route for a "require" path
         *-----------------------------------------------------*/

        /*
         * To access this route a valid login is required.
         * This requirement is defined in ext_typoscript_setup.txt line 9
         */
        # curl -X GET http://localhost:8888/rest/cundd-custom_rest-require
        $router->add(
            Route::get(
                'cundd-custom_rest-require',
                function () {
                    return 'Access Granted';
                }
            )
        );


        /*------------------------------------------------------
         * Sample Routes for Controller "Person"
         *-----------------------------------------------------*/

        /*
         * To define a new "base" route, a specific path is assigned to Route::get
         * instead of the universal $request->getResourceType(). Here it is the path
         * "/cundd-custom_rest-person"
         */

        /* ------------ GET ------------- */

        # curl -X GET http://localhost:8888/rest/cundd-custom_rest-person
        $router->add(
            Route::get(
                '/cundd-custom_rest-person',
                function (RestRequestInterface $request) {
                    return $this->helper->callExtbasePlugin(
                        'customRest',
                        'Cundd',
                        'CustomRest',
                        'Person',
                        'list',
                        []
                    );
                }
            )
        );

        # curl -X GET http://localhost:8888/rest/cundd-custom_rest-person/show/12
        $router->add(
            Route::get(
                '/cundd-custom_rest-person/show/{int}',
                function (RestRequestInterface $request, $int) {
                    $arguments = [
                        'uid' => $int,
                    ];

                    return $this->helper->callExtbasePlugin(
                        'customRest',
                        'Cundd',
                        'CustomRest',
                        'Person',
                        'show',
                        $arguments
                    );
                }
            )
        );

        # curl -X GET http://localhost:8888/rest/cundd-custom_rest-person/firstname/daniel
        $router->add(
            Route::get(
                '/cundd-custom_rest-person/firstname/{slug}',
                function (RestRequestInterface $request, $slug) {
                    $arguments = [
                        'firstName' => $slug,
                    ];

                    return $this->helper->callExtbasePlugin(
                        'customRest',
                        'Cundd',
                        'CustomRest',
                        'Person',
                        'firstName',
                        $arguments
                    );
                }
            )
        );

        # curl -X GET http://localhost:8888/rest/cundd-custom_rest-person/lastname/corn
        $router->add(
            Route::get(
                '/cundd-custom_rest-person/lastname/{slug}',
                function (RestRequestInterface $request, $slug) {
                    $arguments = [
                        'lastName' => $slug,
                    ];

                    return $this->helper->callExtbasePlugin(
                        'customRest',
                        'Cundd',
                        'CustomRest',
                        'Person',
                        'lastName',
                        $arguments
                    );
                }
            )
        );

        # curl -X GET http://localhost:8888/rest/cundd-custom_rest-person/birthday/0000-00-00
        $router->add(
            Route::get(
                '/cundd-custom_rest-person/birthday/{slug}',
                function (RestRequestInterface $request, $slug) {
                    $arguments = [
                        'date' => $slug,
                    ];

                    return $this->helper->callExtbasePlugin(
                        'customRest',
                        'Cundd',
                        'CustomRest',
                        'Person',
                        'birthday',
                        $arguments
                    );
                }
            )
        );


        /*------------------------------------------------------
         * Detailed error routes for empty person path endpoints
         *-----------------------------------------------------*/

        /*
         * Don't do that. Better use an error Object that accepts detailed info. A base error
         * class ist implemented in rest anyway... overwrite/extend that?
         */

        # curl -X GET http://localhost:8888/rest/cundd-custom_rest-person/show
        $router->add(
            Route::get(
                '/cundd-custom_rest-person/show',
                /** @var Request $request */
                function (RestRequestInterface $request) {
                    return [
                        'error' => 'Please add a unique id of the data you are looking for: /person/show/{uid}.',
                    ];
                }
            )
        );

        # curl -X GET http://localhost:8888/rest/cundd-custom_rest-person/lastname
        $router->add(
            Route::get(
                '/cundd-custom_rest-person/lastname',
                function (RestRequestInterface $request) {
                    return $this->responseFactory->createErrorResponse(
                        'Please add a last name: /cundd-custom_rest-person/lastname/{lastName}.',
                        404,
                        $request
                    );
                }
            )
        );

        # curl -X GET http://localhost:8888/rest/cundd-custom_rest-person/firstname
        $router->add(
            Route::get(
                '/cundd-custom_rest-person/firstname',
                /** @var Request $request */
                function (RestRequestInterface $request) {
                    return $this->responseFactory->createErrorResponse(
                        'Please add a first name: /cundd-custom_rest-person/firstname/{firstName}.',
                        404,
                        $request
                    );
                }
            )
        );


        /* ------------ POST ------------- */

        # curl -X POST -H "Content-Type: application/json" -d '{"firstName":"john","lastName":"john"}' http://localhost:8888/rest/customhandler/create
        $router->add(
            Route::post(
                $request->getResourceType() . '/create',
                function (RestRequestInterface $request) {
                    /** @var Request $request */
                    $arguments = [
                        'person' => $request->getSentData(),
                    ];

                    return $this->helper->callExtbasePlugin(
                        'customRest',
                        'Cundd',
                        'CustomRest',
                        'Person',
                        'create',
                        $arguments
                    );
                }
            )
        );

    }
}
