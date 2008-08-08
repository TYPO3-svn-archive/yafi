<?php

########################################################################
# Extension Manager/Repository config file for ext: "yafi"
#
# Auto generated 08-08-2008 14:15
#
# Manual updates:
# Only the data in the array - anything else is removed by next write.
# "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Yet Another Feed Importer',
	'description' => 'Imports RSS/ATOM feeds to TYPO3. Import destinations are configurable and pluggable.',
	'category' => 'plugin',
	'author' => 'Dmitry Dulepov [netcreators]',
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
	'version' => '1.0.6',
	'constraints' => array(
		'depends' => array(
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:28:{s:9:"ChangeLog";s:4:"3d9d";s:21:"class.tx_yafi_api.php";s:4:"9cc5";s:27:"class.tx_yafi_feed_info.php";s:4:"271a";s:27:"class.tx_yafi_feed_item.php";s:4:"1881";s:12:"ext_icon.gif";s:4:"0880";s:17:"ext_localconf.php";s:4:"d86b";s:14:"ext_tables.php";s:4:"815b";s:14:"ext_tables.sql";s:4:"1c41";s:21:"icon_tx_yafi_feed.gif";s:4:"0880";s:25:"icon_tx_yafi_importer.gif";s:4:"2224";s:30:"interface.tx_yafi_importer.php";s:4:"0c21";s:17:"locallang_csh.xml";s:4:"8040";s:16:"locallang_db.xml";s:4:"1949";s:7:"tca.php";s:4:"f230";s:16:"cli/cli_yafi.php";s:4:"1843";s:14:"doc/manual.sxw";s:4:"2f37";s:16:"doc/yafi.graffle";s:4:"3e5c";s:43:"importers/class.tx_yafi_ttnews_importer.php";s:4:"8d79";s:46:"importers/flexform_tx_yafi_ttnews_importer.xml";s:4:"1f13";s:44:"importers/flexform_tx_yafi_zero_importer.xml";s:4:"5ee8";s:25:"lib/simplepie/LICENSE.txt";s:4:"2377";s:35:"lib/simplepie/add-typo3-proxy.patch";s:4:"4897";s:27:"lib/simplepie/simplepie.inc";s:4:"7495";s:36:"lib/simplepie/simplepie.inc.original";s:4:"2a04";s:25:"pi1/class.tx_yafi_pi1.php";s:4:"0441";s:17:"pi1/locallang.xml";s:4:"65fe";s:46:"static/yet_another_feed_importer/constants.txt";s:4:"145e";s:42:"static/yet_another_feed_importer/setup.txt";s:4:"ad8b";}',
	'suggests' => array(
	),
);

?>