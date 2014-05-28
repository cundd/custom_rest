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


use Bullet\App;
use Cundd\Rest\Dispatcher;
use Cundd\Rest\HandlerInterface;
use Cundd\Rest\Request;

/**
 * Example handler
 *
 * @package Cundd\Rest\Handler
 */
class Handler implements HandlerInterface {
	/**
	 * Current request
	 *
	 * @var Request
	 */
	protected $request;

	/**
	 * Sets the current request
	 *
	 * @param \Cundd\Rest\Request $request
	 * @return $this
	 */
	public function setRequest($request) {
		$this->request = $request;
		return $this;
	}

	/**
	 * Returns the current request
	 *
	 * @return \Cundd\Rest\Request
	 */
	public function getRequest() {
		return $this->request;
	}

	/**
	 * Configure the API paths
	 */
	public function configureApiPaths() {
		$dispatcher = Dispatcher::getSharedDispatcher();

		/** @var App $app */
		$app = $dispatcher->getApp();

		/** @var Handler */
		$handler = $this;

		$app->path($dispatcher->getPath(), function ($request) use ($handler, $app, $dispatcher) {
			$handler->setRequest($request);


			$app->path('subpath', function ($request) use ($handler, $app, $dispatcher) {
				# curl -X GET http://localhost:8888/rest/customhandler/subpath
				$getCallback = function ($request) use ($handler, $dispatcher) {
					return array(
						'path' => $dispatcher->getPath(),
						'uri'  => $dispatcher->getUri(),
					);
				};
				$app->get($getCallback);

				# curl -X POST -d '{"username":"johndoe","password":"123456"}' http://localhost:8888/rest/customhandler/subpath
				$postCallback = function ($request) use ($handler) {
					$dispatcher = Dispatcher::getSharedDispatcher();
					return array(
						'path' => $dispatcher->getPath(),
						'uri'  => $dispatcher->getUri(),
						'data' => $dispatcher->getSentData(),
					);
				};
				$app->post($postCallback);
			});

			# curl -X GET http://localhost:8888/rest/customhandler
			$app->get(function ($request) use ($dispatcher) {
				return array(
					'path' => $dispatcher->getPath(),
					'uri'  => $dispatcher->getUri(),
				);
			});
		});
	}
}