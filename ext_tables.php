<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'Cundd.'.$_EXTKEY,
    'myPlugin',
    'myPlugin - List '
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'Custom REST extensions');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_customrest_domain_model_person', 'EXT:custom_rest/Resources/Private/Language/locallang_csh_tx_customrest_domain_model_person.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_customrest_domain_model_person');
//$GLOBALS['TCA']['tx_customrest_domain_model_person'] = array(
//	'ctrl' => array(
//		'title'	=> 'LLL:EXT:custom_rest/Resources/Private/Language/locallang_db.xlf:tx_customrest_domain_model_person',
//		'label' => 'first_name',
//		'tstamp' => 'tstamp',
//		'crdate' => 'crdate',
//		'cruser_id' => 'cruser_id',
//		'dividers2tabs' => TRUE,
//
//		'versioningWS' => 2,
//		'versioning_followPages' => TRUE,
//
//		'languageField' => 'sys_language_uid',
//		'transOrigPointerField' => 'l10n_parent',
//		'transOrigDiffSourceField' => 'l10n_diffsource',
//		'delete' => 'deleted',
//		'enablecolumns' => array(
//			'disabled' => 'hidden',
//			'starttime' => 'starttime',
//			'endtime' => 'endtime',
//		),
//		'searchFields' => 'first_name,last_name,birthday,',
//		'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/Person.php',
//		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_customrest_domain_model_person.gif'
//	),
//);
