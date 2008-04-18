<?php

if (!defined('TYPO3_cliMode')) {
	die('This script cannot be executed directly. Use "typo3/cli_dispatch.phpsh yafi help" for details.');
}

/**
 * Configuration array
 *
 * @global array
 */
$conf = array();

/**
 * Defines if output should be produced or not
 *
 * @global	boolean
 */
$silent = false;

function showHelpAndExit() {
	echo '
typo3/cli_dispatch.php yafi import -p pid [-l limit]
	-p pid      Mandatory. Defines page uid where feed records are located
	-l limit    Limits import to given feeds. Parameters is a comma-separated list of
	-s          Be silent (good for cron jobs)
';
	debug_print_backtrace();
	exit();
}

/**
 * Processes command line and fills $conf
 *
 * @return	void
 */
function processCommandLine() {
	if ($GLOBALS['argc'] < 5 || $GLOBALS['argv'][2] == 'help') {
		showHelpAndExit();
	}
	if (!t3lib_div::inList('im,import', $GLOBALS['argv'][2])) {
		showHelpAndExit();
	}
	for ($i = 3; $i < $GLOBALS['argc']; $i++) {
		if ($GLOBALS['argv'][$i]{0} != '-') {
			showHelpAndExit();
		}
		switch ($GLOBALS['argv'][$i]) {
			case '-p':
				$GLOBALS['conf']['storagePid'] = intval($GLOBALS['argv'][++$i]);
				break;
			case '-l':
				$GLOBALS['conf']['limitToFeeds'] = intval($GLOBALS['argv'][++$i]);
				break;
			case '-s':
				$GLOBALS['silent'] = true;
				break;
			default:
				showHelpAndExit();
				break;
		}
	}
}

processCommandLine();

if (!$conf['storagePid']) {
	showHelpAndExit();
}

require_once(t3lib_extMgm::extPath('yafi', 'class.tx_yafi_api.php'));

$apiObj = t3lib_div::makeInstance('tx_yafi_api');
/* @var $apiObj tx_yafi_api */
if ($apiObj->importFeeds($conf)) {
	if (!$silent) {
		echo 'Import successful. Statistics:' . chr(10);
		print_r($apiObj->getImportStatistics());
	}
}
else {
	if (!$silent) {
		echo 'Import failed';
	}
}
if (!$silent) {
	echo chr(10);
}

?>