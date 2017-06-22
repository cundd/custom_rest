<?php

namespace Cundd\CustomRest\Rest;

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
     *
     * @return string
     */
    public function callExtbasePlugin(
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

        /** @var \TYPO3\CMS\Extbase\Core\Bootstrap $bootstrap */
        $bootstrap = $this->objectManager->get(\TYPO3\CMS\Extbase\Core\Bootstrap::class);

        $response = $bootstrap->run('', $configuration);

        return $this->responseFactory->createResponse($response, 200);
    }
}
