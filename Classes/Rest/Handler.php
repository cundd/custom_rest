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

		/** @var Handler */
		$handler = $this;

		$dispatcher->registerPath($dispatcher->getRequest()->path(), function ($request) use ($handler, $dispatcher) {
			$handler->setRequest($request);

			# curl -X GET http://your-domain.com/rest/customhandler
			$dispatcher->registerGetMethod(function ($request) use ($dispatcher) {
				/** @var Request $request */
				return array(
					'path' => $request->path(),
					'uri'  => $request->uri(),
				);
			});

			$dispatcher->registerPath('subpath', function ($request) use ($handler, $dispatcher) {
				# curl -X GET http://your-domain.com/rest/customhandler/subpath
				$getCallback = function ($request) use ($handler, $dispatcher) {
					/** @var Request $request */
					return array(
						'path' => $request->path(),
						'uri'  => $request->uri(),
					);
				};
				$dispatcher->registerGetMethod($getCallback);

				# curl -X POST -d '{"username":"johndoe","password":"123456"}' http://your-domain.com/rest/customhandler/subpath
				$postCallback = function ($request) use ($handler) {
					/** @var Request $request */
					return array(
						'path' => $request->path(),
						'uri'  => $request->uri(),
						'data' => $request->getSentData(),
					);
				};
				$dispatcher->registerPostMethod($postCallback);
			});
		});
	}
}
