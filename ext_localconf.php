<?php

if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

// My Plugin
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Cundd.' . $_EXTKEY,
	'myPlugin',
	['Example' => 'create'],
	['Example' => '']
);
