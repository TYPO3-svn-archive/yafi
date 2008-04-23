<?php

########################################################################
# Extension Manager/Repository config file for ext: "yafi"
#
# Auto generated 18-04-2008 14:51
#
# Manual updates:
# Only the data in the array - anything else is removed by next write.
# "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Yet Another Feed Importer',
	'description' => 'Imports RSS/ATOM feeds to TYPO3. Import destinations are configurable and pluggable.',
	'category' => 'plugin',
	'author' => 'Dmitry Dulepov',
	'author_email' => 'dmitry@typo3.org',
	'shy' => '',
	'dependencies' => '',
	'conflicts' => '',
	'priority' => '',
	'module' => '',
	'state' => 'beta',
	'internal' => '',
	'uploadfolder' => 0,
	'createDirs' => 'typo3temp/tx_yafi/feed_cache',
	'modify_tables' => 'tt_news',
	'clearCacheOnLoad' => 0,
	'lockType' => '',
	'author_company' => 'Netcreators BV',
	'version' => '0.1.0',
	'constraints' => array(
		'depends' => array(
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:24:{s:9:"ChangeLog";s:4:"ca84";s:21:"class.tx_yafi_api.php";s:4:"5c56";s:27:"class.tx_yafi_feed_info.php";s:4:"6de5";s:27:"class.tx_yafi_feed_item.php";s:4:"35ce";s:12:"ext_icon.gif";s:4:"0880";s:17:"ext_localconf.php";s:4:"57da";s:14:"ext_tables.php";s:4:"71ed";s:14:"ext_tables.sql";s:4:"0c6b";s:21:"icon_tx_yafi_feed.gif";s:4:"0880";s:25:"icon_tx_yafi_importer.gif";s:4:"2224";s:30:"interface.tx_yafi_importer.php";s:4:"d547";s:17:"locallang_csh.xml";s:4:"d7f5";s:16:"locallang_db.xml";s:4:"c0a6";s:7:"tca.php";s:4:"f07e";s:16:"cli/cli_yafi.php";s:4:"68f9";s:43:"importers/class.tx_yafi_ttnews_importer.php";s:4:"8624";s:46:"importers/flexform_tx_yafi_ttnews_importer.xml";s:4:"f8bf";s:44:"importers/flexform_tx_yafi_zero_importer.xml";s:4:"5ee8";s:25:"lib/simplepie/LICENSE.txt";s:4:"2377";s:27:"lib/simplepie/simplepie.inc";s:4:"dae5";s:25:"pi1/class.tx_yafi_pi1.php";s:4:"94b8";s:17:"pi1/locallang.xml";s:4:"65fe";s:46:"static/yet_another_feed_importer/constants.txt";s:4:"f0cb";s:42:"static/yet_another_feed_importer/setup.txt";s:4:"c282";}',
	'suggests' => array(
	),
);

?>