<?php

/* $Id$ */

if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

$TCA['tx_yafi_feed'] = array (
	'ctrl' => array (
		'title'     => 'LLL:EXT:yafi/locallang_db.xml:tx_yafi_feed',
		'label'     => 'title',
		'label_alt'	=> 'title,url',
		'tstamp'    => 'tstamp',
		'crdate'    => 'crdate',
		'cruser_id' => 'cruser_id',
		'default_sortby' => 'ORDER BY title,url,crdate',
		'delete' => 'deleted',
		'enablecolumns' => array (
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		),
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY).'tca.php',
		'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY).'icon_tx_yafi_feed.gif',
	),
);

t3lib_extMgm::allowTableOnStandardPages('tx_yafi_feed');
t3lib_extMgm::addLLrefForTCAdescr('tx_yafi_feed', 'EXT:yafi/locallang_csh.xml');


$TCA['tx_yafi_importer'] = array (
	'ctrl' => array (
		'title'     => 'LLL:EXT:yafi/locallang_db.xml:tx_yafi_importer',
		'label'     => 'importer_type',
		'tstamp'    => 'tstamp',
		'crdate'    => 'crdate',
		'cruser_id' => 'cruser_id',
		'default_sortby' => 'ORDER BY crdate',
		'delete' => 'deleted',
		'requestUpdate' => 'importer_type',
		'hideTable' => true,
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY).'tca.php',
		'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY).'icon_tx_yafi_importer.gif',
	),
);

t3lib_extMgm::allowTableOnStandardPages('tx_yafi_importer');
t3lib_extMgm::addLLrefForTCAdescr('tx_yafi_importer', 'EXT:yafi/locallang_csh.xml');

require_once(t3lib_extMgm::extPath('yafi', 'class.tx_yafi_api.php'));

if (t3lib_extMgm::isLoaded('tt_news')) {
	tx_yafi_api::registerImporter('EXT:yafi/importers/class.tx_yafi_ttnews_importer.php:tx_yafi_ttnews_importer');

	// New columns
	$tempColumns = array (
		'tx_yafi_import_id' => Array (
			'exclude' => 0,
			'label' => 'LLL:EXT:yafi/locallang_db.xml:tx_yafi_import_id',
			'config' => array (
				'type'     => 'passthru',
			)
		),
	);
	t3lib_div::loadTCA('tt_news');
	t3lib_extMgm::addTCAcolumns('tt_news', $tempColumns, 1);
}

t3lib_div::loadTCA('tt_content');
$TCA['tt_content']['types']['list']['subtypes_excludelist'][$_EXTKEY . '_pi1'] = 'layout,select_key,pages';

t3lib_extMgm::addPlugin(array('LLL:EXT:yafi/locallang_db.xml:tt_content.list_type_pi1', $_EXTKEY . '_pi1'), 'list_type');

t3lib_extMgm::addStaticFile($_EXTKEY, 'static/yet_another_feed_importer/', 'Yet Another Feed Importer');

?>