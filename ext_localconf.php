<?php

/* $Id$ */

if (!defined ('TYPO3_MODE')) {
 	die ('Access denied.');
}
t3lib_extMgm::addUserTSConfig('
	options.saveDocNew.tx_yafi_feed=1
');

t3lib_extMgm::addPItoST43($_EXTKEY,'pi1/class.tx_yafi_pi1.php','_pi1','list_type',0);

if (t3lib_extMgm::isLoaded('tt_news')) {
	require_once(t3lib_extMgm::extPath('yafi', 'class.tx_yafi_api.php'));
	tx_yafi_api::registerImporter('EXT:yafi/importers/class.tx_yafi_ttnews_importer.php:tx_yafi_ttnews_importer');
}

$TYPO3_CONF_VARS['SC_OPTIONS']['GLOBAL']['cliKeys']['yafi'] = array('EXT:yafi/cli/cli_yafi.php','_CLI_yafi');

?>