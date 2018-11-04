<?php

namespace Cundd\CustomRest\Rest;

use Cundd\Rest\Http\Header;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Helper
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
     * Calls a extbase plugin
     *
     * @param string $pluginName     the name of the plugin like configured in ext_localconf.php
     * @param string $vendorName     the name of the vendor (if no vendor use '')
     * @param string $extensionName  the name of the extension
     * @param string $controllerName the name of the controller
     * @param string $actionName     the name of the action to call
     * @param array  $arguments      the arguments to pass to the action
     * @return ResponseInterface
     */
    public function callExtbasePlugin(
        $pluginName,
        $vendorName,
        $extensionName,
        $controllerName,
        $actionName,
        array $arguments
    ) {
        if (isset($GLOBALS['TYPO3_REQUEST'])) {
            $GLOBALS['TYPO3_REQUEST'] = $this->patchRequest(
                $GLOBALS['TYPO3_REQUEST'],
                $pluginName,
                $extensionName,
                $controllerName,
                $actionName,
                $arguments
            );
        } else {
            $this->patchGlobals($pluginName, $extensionName, $controllerName, $actionName, $arguments);
        }

        $configuration = [
            'extensionName' => $extensionName,
            'pluginName'    => $pluginName,
        ];

        if (!empty($vendorName)) {
            $configuration['vendorName'] = $vendorName;
        }

        /** @var \TYPO3\CMS\Extbase\Core\Bootstrap $bootstrap */
        $bootstrap = $this->objectManager->get(\TYPO3\CMS\Extbase\Core\Bootstrap::class);

        $extbaseResult = $bootstrap->run('', $configuration);
        $response = $this->responseFactory->createResponse($extbaseResult, 200);

        return $response->withHeader(Header::CONTENT_TYPE, 'application/json');
    }

    /**
     * @param ServerRequestInterface $request
     * @param string                 $pluginName
     * @param string                 $extensionName
     * @param string                 $controllerName
     * @param string                 $actionName
     * @param array                  $arguments
     * @return ServerRequestInterface
     */
    private function patchRequest(
        ServerRequestInterface $request,
        $pluginName,
        $extensionName,
        $controllerName,
        $actionName,
        array $arguments
    ) {
        $queryParams = $request->getQueryParams();
        $pluginNamespace = $this->getPluginNamespace($pluginName, $extensionName);
        $this->patchQueryParams($queryParams, $controllerName, $actionName, $arguments, $pluginNamespace);

        return $request->withQueryParams($queryParams);
    }

    /**
     * @param string $pluginName
     * @param string $extensionName
     * @param string $controllerName
     * @param string $actionName
     * @param array  $arguments
     */
    private function patchGlobals(
        $pluginName,
        $extensionName,
        $controllerName,
        $actionName,
        array $arguments
    ) {
        $pluginNamespace = $this->getPluginNamespace($pluginName, $extensionName);
        $this->patchQueryParams($_POST, $controllerName, $actionName, $arguments, $pluginNamespace);
    }

    /**
     * @param string $pluginName
     * @param string $extensionName
     * @return string
     */
    private function getPluginNamespace($pluginName, $extensionName)
    {
        return strtolower('tx_' . $extensionName . '_' . $pluginName);
    }

    /**
     * @param array  $queryParams
     * @param string $controllerName
     * @param string $actionName
     * @param array  $arguments
     * @param string $pluginNamespace
     */
    private function patchQueryParams(
        array &$queryParams,
        $controllerName,
        $actionName,
        array $arguments,
        $pluginNamespace
    ) {
        $queryParams[$pluginNamespace]['controller'] = $controllerName;
        $queryParams[$pluginNamespace]['action'] = $actionName;

        $keys = array_keys($arguments);
        foreach ($keys as $key) {
            $queryParams[$pluginNamespace][$key] = $arguments[$key];
        }
    }
}
