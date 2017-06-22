<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'Cundd.'.$_EXTKEY,
    'customRest',
    'CustomRest - List '
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_customrest_domain_model_person', 'EXT:custom_rest/Resources/Private/Language/locallang_csh_tx_customrest_domain_model_person.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_customrest_domain_model_person');
