<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

$TCA['tx_yafi_feed'] = array (
	'ctrl' => $TCA['tx_yafi_feed']['ctrl'],
	'interface' => array (
		'showRecordFieldList' => 'hidden,starttime,endtime,url,import_interval,expires,last_import,importer_config'
	),
	'feInterface' => $TCA['tx_yafi_feed']['feInterface'],
	'columns' => array (
		'hidden' => array (
			'exclude' => 1,
			'label'   => 'LLL:EXT:yafi/locallang_db.xml:tx_yafi_feed.disable',
			'config'  => array (
				'type'    => 'check',
				'default' => '0'
			)
		),
		'starttime' => array (
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.starttime',
			'config'  => array (
				'type'     => 'input',
				'size'     => '8',
				'max'      => '20',
				'eval'     => 'date',
				'default'  => '0',
				'checkbox' => '0'
			)
		),
		'endtime' => array (
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.endtime',
			'config'  => array (
				'type'     => 'input',
				'size'     => '8',
				'max'      => '20',
				'eval'     => 'date',
				'checkbox' => '0',
				'default'  => '0',
				'range'    => array (
					'upper' => mktime(0, 0, 0, 12, 31, 2020),
					'lower' => mktime(0, 0, 0, date('m')-1, date('d'), date('Y'))
				)
			)
		),
		'url' => array (
			'exclude' => 1,
			'label' => 'LLL:EXT:yafi/locallang_db.xml:tx_yafi_feed.url',
			'config' => array (
				'type' => 'input',
				'size' => '30',
				'eval' => 'required,trim,nospace',
			)
		),
		'import_interval' => array (
			'exclude' => 1,
			'label' => 'LLL:EXT:yafi/locallang_db.xml:tx_yafi_feed.import_interval',
			'config' => array (
				'type' => 'input',
				'size' => '15',
				'eval' => 'required,trim',
				'default' => '+1 hour',
			)
		),
		'expires' => array (
			'exclude' => 1,
			'label' => 'LLL:EXT:yafi/locallang_db.xml:tx_yafi_feed.expires',
			'config' => array (
				'type' => 'input',
				'size' => '15',
				'eval' => 'trim,',
				'default' => '+6 months',
			)
		),
		'last_import' => array (
			'exclude' => 1,
			'label' => 'LLL:EXT:yafi/locallang_db.xml:tx_yafi_feed.last_import',
			'config' => array (
				'type' => 'input',
				'size' => '15',
				'eval' => 'datetime',
				'readOnly' => true,
			)
		),
		'last_import_localtime' => array (
			'exclude' => 1,
			'label' => 'LLL:EXT:yafi/locallang_db.xml:tx_yafi_feed.last_import_localtime',
			'config' => array (
				'type' => 'input',
				'size' => '15',
				'eval' => 'datetime',
				'readOnly' => true,
			)
		),
		'importer_config' => array (
			'exclude' => 1,
			'label' => 'LLL:EXT:yafi/locallang_db.xml:tx_yafi_feed.importer_config',
			'config' => array (
				'type' => 'inline',
				'foreign_table' => 'tx_yafi_importer',
				'foreign_table_field' => 'irre_parent_table',
				'foreign_label' => 'importer_type',
				'foreign_field' => 'irre_parent_uid',
				'minitems'   => '0',
				'maxitems'   => '10',
				'appearance' => array(
								'collapseAll'           => '1',
								'expandSingle'          => '1',
								'useSortable'           => '1',
								'newRecordLinkAddTitle' => '1',
								'newRecordLinkPosition' => 'top',
								'useCombination'        => '0',
							)
			)
		),
	),
	'types' => array (
		'0' => array('showitem' => 'url;;;;1-1-1, hidden;;1, import_interval;;2, importer_config')
	),
	'palettes' => array (
		'1' => array('showitem' => 'starttime, endtime'),
		'2' => array('showitem' => 'last_import, last_import_localtime'),
	)
);



$TCA['tx_yafi_importer'] = array (
	'ctrl' => $TCA['tx_yafi_importer']['ctrl'],
	'interface' => array (
		'showRecordFieldList' => 'importer_type,importer_conf,irre_parent_uid,irre_parent_table'
	),
	'feInterface' => $TCA['tx_yafi_importer']['feInterface'],
	'columns' => array (
		'importer_type' => array (
			'exclude' => 1,
			'label' => 'LLL:EXT:yafi/locallang_db.xml:tx_yafi_importer.importer_type',
			'config' => array (
				'type' => 'select',
				'items' => array (
					array('LLL:EXT:yafi/locallang_db.xml:tx_yafi_importer.importer_type.I.0', '0'),
				),
				'itemsProcFunc' => 'tx_yafi_api->importerTypeItemsProcFunc',
				'size' => 1,
				'maxitems' => 1,
			)
		),
		'importer_conf' => array (
			'exclude' => 1,
			'label' => 'LLL:EXT:yafi/locallang_db.xml:tx_yafi_importer.importer_conf',
			'config' => array (
				'type' => 'flex',
				'ds_pointerField' => 'importer_type',
				'ds' => array (
					'default' => 'FILE:EXT:yafi/importers/flexform_tx_yafi_zero_importer.xml',
				),
			)
		),
		'irre_parent_uid' => array (
			'config' => array (
				'type' => 'passthrough',
			)
		),
		'irre_parent_table' => array (
			'config' => array (
				'type' => 'passthrough',
			)
		),
	),
	'types' => array (
		'0' => array('showitem' => 'importer_type;;;;1-1-1, importer_conf, irre_parent_uid, irre_parent_table')
	),
	'palettes' => array (
		'1' => array('showitem' => '')
	)
);
?>