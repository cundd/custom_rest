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
     * @inheritDoc
     */
    public function configureRoutes(RouterInterface $router, RestRequestInterface $request)
    {
        # curl -X GET http://localhost:8888/rest/customhandler/
        $router->add(
            Route::get(
                $request->getResourceType(),
                function (RestRequestInterface $request) {
                    return array(
                        'path'         => $request->getPath(),
                        'uri'          => (string)$request->getUri(),
                        'resourceType' => (string)$request->getResourceType(),
                    );
                }
            )
        );
        # curl -X GET http://localhost:8888/rest/cundd-custom_rest-require/
        $router->add(
            Route::get(
                'cundd-custom_rest-require',
                function () {
                    return 'Access Granted';
                }
            )
        );

        # curl -X GET http://localhost:8888/rest/customhandler/subpath
        $router->add(
            Route::get(
                $request->getResourceType() . '/subpath',
                function (RestRequestInterface $request) {
                    return array(
                        'path'         => $request->getPath(),
                        'uri'          => (string)$request->getUri(),
                        'resourceType' => (string)$request->getResourceType(),
                    );
                }
            )
        );

        # curl -X POST -d '{"username":"johndoe","password":"123456"}' http://localhost:8888/rest/customhandler/subpath
        $router->add(
            Route::post(
                $request->getResourceType() . '/subpath',
                function (RestRequestInterface $request) {
                    return array(
                        'path'         => $request->getPath(),
                        'uri'          => (string)$request->getUri(),
                        'resourceType' => (string)$request->getResourceType(),
                        'data'         => $request->getSentData(),
                    );
                }
            )
        );

        # curl -X POST -H "Content-Type: application/json" -d '{"firstName":"john","lastName":"john"}' http://localhost:8888/rest/customhandler/create
        $router->add(
            Route::post(
                $request->getResourceType() . '/create',
                function (RestRequestInterface $request) {
                    /** @var Request $request */
                    $arguments = [
                        'person' => $request->getSentData(),
                    ];

                    return $this->callExtbasePlugin(
                        'myPlugin',
                        'Cundd',
                        'CustomRest',
                        'Example',
                        'create',
                        $arguments
                    );
                }
            )
        );
    }

    /**
     * calls a extbase plugin
     *
     * @param string $pluginName     the name of the plugin like configured in ext_localconf.php
     * @param string $vendorName     the name of the vendor (if no vendor use '')
     * @param string $extensionName  the name of the extension
     * @param string $controllerName the name of the controller
     * @param string $actionName     the name of the action to call
     * @param array  $arguments      the arguments to pass to the action
     * @return string
     */
    protected function callExtbasePlugin(
        $pluginName,
        $vendorName,
        $extensionName,
        $controllerName,
        $actionName,
        $arguments
    ) {
        $pluginNamespace = strtolower('tx_' . $extensionName . '_' . $pluginName);

        $_POST[$pluginNamespace]['controller'] = $controllerName;
        $_POST[$pluginNamespace]['action'] = $actionName;

        $keys = array_keys($arguments);
        foreach ($keys as $key) {
            $_POST[$pluginNamespace][$key] = $arguments[$key];
        }

        $configuration = [
            'extensionName' => $extensionName,
            'pluginName'    => $pluginName,
        ];

        if (!empty($vendorName)) {
            $configuration['vendorName'] = $vendorName;
        }

        $bootstrap = $this->objectManager->get(\TYPO3\CMS\Extbase\Core\Bootstrap::class);

        $response = $bootstrap->run('', $configuration);

        return $this->responseFactory->createResponse($response, 200);
    }
}
