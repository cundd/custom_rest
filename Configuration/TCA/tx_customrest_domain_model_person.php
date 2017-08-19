<?php
if (!defined('TYPO3_MODE')) {
    die ('Access denied.');
}

return [
    'ctrl' =>
        [
            'title'         => 'LLL:EXT:custom_rest/Resources/Private/Language/locallang_db.xlf:tx_customrest_domain_model_person',
            'label'         => 'first_name',
            'tstamp'        => 'tstamp',
            'crdate'        => 'crdate',
            'cruser_id'     => 'cruser_id',
            'dividers2tabs' => true,

            'versioningWS'           => 2,
            'versioning_followPages' => true,

            'languageField'            => 'sys_language_uid',
            'transOrigPointerField'    => 'l10n_parent',
            'transOrigDiffSourceField' => 'l10n_diffsource',
            'delete'                   => 'deleted',
            'enablecolumns'            => [
                'disabled'  => 'hidden',
                'starttime' => 'starttime',
                'endtime'   => 'endtime',
            ],
            'searchFields'             => 'first_name,last_name,birthday,',
            'iconfile'                 => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('custom_rest')
                . 'Resources/Public/Icons/tx_customrest_domain_model_person.gif',
        ],

    'interface' => [
        'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, first_name, last_name, birthday',
    ],
    'types'     => [
        '1' => ['showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden;;1, first_name, last_name, birthday, --div--;LLL:EXT:cms/locallang_ttc.xlf:tabs.access, starttime, endtime'],
    ],
    'palettes'  => [
        '1' => ['showitem' => ''],
    ],

    'columns' => [
        'sys_language_uid' => [
            'exclude' => 1,
            'label'   => 'LLL:EXT:lang/locallang_general.xlf:LGL.language',
            'config'  => [
                'type'                => 'select',
                'foreign_table'       => 'sys_language',
                'foreign_table_where' => 'ORDER BY sys_language.title',
                'items'               => [
                    ['LLL:EXT:lang/locallang_general.xlf:LGL.allLanguages', -1],
                    ['LLL:EXT:lang/locallang_general.xlf:LGL.default_value', 0],
                ],
            ],
        ],
        'l10n_parent'      => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'exclude'     => 1,
            'label'       => 'LLL:EXT:lang/locallang_general.xlf:LGL.l18n_parent',
            'config'      => [
                'type'                => 'select',
                'items'               => [
                    ['', 0],
                ],
                'foreign_table'       => 'tx_customrest_domain_model_person',
                'foreign_table_where' => 'AND tx_customrest_domain_model_person.pid=###CURRENT_PID### AND tx_customrest_domain_model_person.sys_language_uid IN (-1,0)',
            ],
        ],
        'l10n_diffsource'  => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        't3ver_label'      => [
            'label'  => 'LLL:EXT:lang/locallang_general.xlf:LGL.versionLabel',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'max'  => 255,
            ],
        ],
        'hidden'           => [
            'exclude' => 1,
            'label'   => 'LLL:EXT:lang/locallang_general.xlf:LGL.hidden',
            'config'  => [
                'type' => 'check',
            ],
        ],
        'starttime'        => [
            'exclude'   => 1,
            'l10n_mode' => 'mergeIfNotBlank',
            'label'     => 'LLL:EXT:lang/locallang_general.xlf:LGL.starttime',
            'config'    => [
                'type'     => 'input',
                'size'     => 13,
                'max'      => 20,
                'eval'     => 'datetime',
                'checkbox' => 0,
                'default'  => 0,
                'range'    => [
                    'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y')),
                ],
            ],
        ],
        'endtime'          => [
            'exclude'   => 1,
            'l10n_mode' => 'mergeIfNotBlank',
            'label'     => 'LLL:EXT:lang/locallang_general.xlf:LGL.endtime',
            'config'    => [
                'type'     => 'input',
                'size'     => 13,
                'max'      => 20,
                'eval'     => 'datetime',
                'checkbox' => 0,
                'default'  => 0,
                'range'    => [
                    'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y')),
                ],
            ],
        ],
        'first_name'       => [
            'exclude' => 1,
            'label'   => 'LLL:EXT:custom_rest/Resources/Private/Language/locallang_db.xlf:tx_customrest_domain_model_person.first_name',
            'config'  => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ],
        ],
        'last_name'        => [
            'exclude' => 1,
            'label'   => 'LLL:EXT:custom_rest/Resources/Private/Language/locallang_db.xlf:tx_customrest_domain_model_person.last_name',
            'config'  => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ],
        ],
        'birthday'         => [
            'exclude' => 1,
            'label'   => 'LLL:EXT:custom_rest/Resources/Private/Language/locallang_db.xlf:tx_customrest_domain_model_person.birthday',
            'config'  => [
                'dbType'   => 'date',
                'type'     => 'input',
                'size'     => 7,
                'eval'     => 'date',
                'checkbox' => 0,
                'default'  => '0000-00-00',
            ],
        ],
    ],
];
