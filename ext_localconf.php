<?php

if (!defined('TYPO3_MODE')) {
    die ('Access denied.');
}

// My Plugin
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'Cundd.' . $_EXTKEY,
    'customRest',
    ['Person' => 'list,show,firstName,lastName,birthday,create'],
    ['Person' => '']
);
