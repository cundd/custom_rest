<?php

if (!defined('TYPO3_MODE')) {
    die ('Access denied.');
}
call_user_func(
    function () {
        // My Plugin
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'Cundd.CustomRest',
            'customRest',
            ['Person' => 'list,show,firstName,lastName,birthday,create'],
            ['Person' => '']
        );
    }
);