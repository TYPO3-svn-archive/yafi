<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2008 Dmitry Dulepov <dmitry@typo3.org>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/
/**
 * $Id$
 *
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 * Hint: use extdeveval to insert/update function index above.
 */


require_once(t3lib_extMgm::extPath('yafi', 'interface.tx_yafi_importer.php'));

// Include ATOM/RSS importer (SimplePie)
if (!class_exists('SimplePie')) {
	require_once(t3lib_extMgm::extPath('yafi', 'lib/simplepie/simplepie.inc'));
}

require_once(t3lib_extMgm::extPath('yafi', 'class.tx_yafi_feed_info.php'));
require_once(t3lib_extMgm::extPath('yafi', 'class.tx_yafi_feed_item.php'));

/**
 * Class/Function which manipulates the item-array for table/field tx_yafi_importer_importer_type.
 *
 * @author	Dmitry Dulepov <dmitry@typo3.org>
 * @package	TYPO3
 * @subpackage	tx_yafi
 */
class tx_yafi_api {

	/** Holds references to registered importers */
	protected static $registeredImporters = array();

	const STATS_IMPORTED_FEEDS = 'imported_feeds';

	const STATS_IGNORED_FEEDS = 'ignored_feeds';

	const STATS_IMPORTED_ARTICLES = 'imported_articles';

	const STATS_IGNORED_ARTICLES = 'ignored_articles';

	protected $importStats = array();

	/**
	 * Adds registered importers to "importer type" control
	 *
	 * @param	array		$$params	Parameters to the function
	 * @param	object		$pObj	Reference to parent object
	 * @return	void
	 */
	public function importerTypeItemsProcFunc(array &$params, &$pObj)	{
		self::loadImporters();
		foreach (self::$registeredImporters as $key => $obj) {
			/* @var $obj tx_yafi_importer */
			$params['items'][] = array($pObj->sL($obj->getTitle()), $key, $obj->getIcon());
		}
	}

	/**
	 * Registers importer with extension.
	 *
	 * @param	string	$className	Class name/definition of importer
	 * @see	t3lib_div::getUserObj
	 */
	public static function registerImporter($className) {
		$class = t3lib_div::getUserObj($className);
		if ($class instanceof tx_yafi_importer) {
			/* @var $class tx_yafi_importer */
			$key = $class->getKey();

			// Register it
			self::$registeredImporters[$key] = $class;

			// Add configuration DS
			t3lib_div::loadTCA('tx_yafi_importer');
			$GLOBALS['TCA']['tx_yafi_importer']['columns']['importer_conf']['config']['ds'][$key] = $class->getFlexFormDS();
		}
//		self::$registeredImporters[$className] = $className;
		return true;
	}

	protected static function loadImporters() {
		/*
		$result = array();
		foreach (self::$registeredImporters as $className) {
			if (is_string($className)) {
				$class = t3lib_div::getUserObj($className);
				if ($class instanceof tx_yafi_importer) {
		*/			/* @var $class tx_yafi_importer */
		/*			$key = $class->getKey();

					// Register it
					$result[$key] = $class;

					// Add configuration DS
					t3lib_div::loadTCA('tx_yafi_importer');
					$GLOBALS['TCA']['tx_yafi_importer']['columns']['importer_conf']['config']['ds'][$key] = $class->getFlexFormDS();
				}
			}
		}
		if (count($result) > 0) {
			self::$registeredImporters = $result;
		}
		*/
	}

	/**
	 * Imports feeds that match to configuration.
	 *
	 * @param	array		$conf	Configuration (see tx_yafi_pi1 TS)
	 * @return	boolean		true if successful
	 */
	public function importFeeds(array $conf) {
		// Include here or it will break XCLASSes!
		require_once(PATH_t3lib . 'class.t3lib_befunc.php');
		// Check configuration and setup extra conditions
		if (!t3lib_div::testInt($conf['storagePid'])) {
			t3lib_div::devLog('tx_yafi_api::importFeeds(): no valid storagePid in configuration', 'yafi', 3);
			return false;
		}

		// Here to allow proper XCLASSing
//		global $TYPO3_CONF_VARS;
//		require_once(t3lib_extMgm::extPath('yafi', 'class.tx_yafi_feed_info.php'));
//		require_once(t3lib_extMgm::extPath('yafi', 'class.tx_yafi_feed_item.php'));

		self::loadImporters();

		if (trim($conf['limitToFeeds'])) {
			$conf['limitToFeeds'] = t3lib_div::trimExplode(',', $conf['limitToFeeds']);
			foreach ($conf['limitToFeeds'] as $k => $v) {
				$conf['limitToFeeds'][$k] = intval($v);
			}
			$conf['limitToFeeds'] = array_unique($conf['limitToFeeds']);
		}
		if (TYPO3_DLOG) {
			t3lib_div::devLog('importFeed starts', 'yafi', 0, array('conf' => $conf));
		}
		$feedInfoClassName = t3lib_div::makeInstanceClassName('tx_yafi_feed_info');
		$feedItemClassName = t3lib_div::makeInstanceClassName('tx_yafi_feed_item');
		$this->importStats = array(
			self::STATS_IMPORTED_FEEDS => 0,
			self::STATS_IGNORED_FEEDS => 0,
			self::STATS_IMPORTED_ARTICLES => 0,
			self::STATS_IGNORED_ARTICLES => 0
		);

		// Get feeds
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('uid,title,url,import_interval,last_import,last_import_localtime',
					'tx_yafi_feed',
					'pid=' . $conf['storagePid'] . ' AND importer_config>0' .
						(is_array($conf['limitToFeeds']) ? ' AND uid IN (' . implode(',', $conf['limitToFeeds']) . ')' : '') .
						t3lib_BEfunc::BEenableFields('tx_yafi_feed') . t3lib_BEfunc::deleteClause('tx_yafi_feed')
					);
		$feedNumberLimit = intval($conf['numberLimit']);
		if ($feedNumberLimit <= 0) {
			$feedNumberLimit = PHP_INT_MAX;
		}
		elseif (TYPO3_DLOG) {
			t3lib_div::devLog(sprintf('Will import %d feeds max', $feedNumberLimit), 'yafi');
		}
		while ($this->importStats[self::STATS_IMPORTED_FEEDS] < $feedNumberLimit && false !== ($feed = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res))) {
			// Check if import interval allows us to import now
			if ($feed['import_interval'] && strtotime($feed['import_interval'], $feed['last_import']) > time()) {
				// To early to import this feed
				$this->importStats[self::STATS_IGNORED_FEEDS]++;
				if (TYPO3_DLOG) {
					t3lib_div::devLog(sprintf('Feed "%s" (%s) is ignored because its time did not come yet',
							$feed['title'], $feed['url']), 'yafi');
				}
				continue;
			}
			if (TYPO3_DLOG) {
				t3lib_div::devLog(sprintf('Feed "%s" (%s) is scheduled for import',
						$feed['title'], $feed['url']), 'yafi');
			}
			$this->importStats[self::STATS_IMPORTED_FEEDS]++;

			// Do we have any enabled importers for this feed?
			// Notice: we do not check pid because IRRE saves these records on the same page.
			$importers = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows('importer_type,importer_conf',
					'tx_yafi_importer',
					'irre_parent_uid=' . $feed['uid'] .
						' AND importer_type<>\'\'' .
//						t3lib_BEfunc::BEenableFields('tx_yafi_importer') .
						t3lib_BEfunc::deleteClause('tx_yafi_importer')
					);
			if (count($importers) > 0) {
				// Set configuration for each importer
				foreach ($importers as $importerData) {
					if (isset(self::$registeredImporters[$importerData['importer_type']])) {
						if (TYPO3_DLOG) {
							t3lib_div::devLog(sprintf('Initializing importer "%s"', $importerData['importer_type']), 'yafi');
						}
						$importer = &self::$registeredImporters[$importerData['importer_type']];
						/* @var $importer tx_yafi_importer */
						$info = new $feedInfoClassName($feed['url']);
						/* @var $info tx_yafi_feed_info */
						$importer->importStart($info, $importerData['importer_conf']);
					}
				}

				// Import it

				// Prepare SimplePie
				if ($feed['import_interval']) {
					$cache_timeout = strtotime($feed['import_interval'], 0) - 1;
				}
				else {
					// TODO Move to extension configuration!
					$cache_timeout = 24*60*60 - 1;
				}
				$simplePie = new SimplePie($feed['url'], PATH_site . 'typo3temp/tx_yafi/feed_cache', $cache_timeout);
				if ($GLOBALS['TYPO3_CONF_VARS']['BE']['forceCharset']) {
					$simplePie->set_output_encoding($GLOBALS['TYPO3_CONF_VARS']['BE']['forceCharset']);
				}
				$lastImportedLocalTime = $feed['last_import_localtime'];
				foreach ($simplePie->get_items() as $item) {
					/* @var $item SimplePie_Item */
					// Firsts make a quich check for item date. We access SimplePie_item directly because using
					// tx_yafi_feed_item will also parse description/content and it is slower if item does
					// not have to be imported

					$time = $item->get_date('U');
					if (($time == 0 && $feed['last_import_localtime'] == 0) || $time > $feed['last_import_localtime']) {
						$feedItem = new $feedItemClassName($item);
						/* @var $feedItem tx_yafi_feed_item */
						foreach ($importers as $importerData) {
							if (isset(self::$registeredImporters[$importerData['importer_type']])) {
								$importer = &self::$registeredImporters[$importerData['importer_type']];
								/* @var $importer tx_yafi_importer */
								if (!$importer->isImported($feedItem->getId())) {
									$importer->import($feed, $feedItem);
									if (TYPO3_DLOG) {
										t3lib_div::devLog(sprintf('Imported item "%s" with importer "%s"', $feedItem->getTitle(), $importerData['importer_type']), 'yafi');
									}
									$this->importStats[self::STATS_IMPORTED_ARTICLES]++;
								}
								else {
									if (TYPO3_DLOG) {
										t3lib_div::devLog(sprintf('Attempt to import duplicate item. Item is is "%s"', $feedItem->getId()), 'yafi');
									}
									$this->importStats[self::STATS_IGNORED_ARTICLES]++;
								}
							}
						}
						if ($time > $lastImportedLocalTime) {
							$lastImportedLocalTime = $time;
						}
						unset($feedItem);
					}
					else {
						$this->importStats[self::STATS_IGNORED_ARTICLES]++;
						if (TYPO3_DLOG) {
							t3lib_div::devLog(sprintf('Skipping item "%s" (already imported)', $item->get_title()), 'yafi');
						}
					}
				}
				// Update feed info
				$fields = array('last_import' => time());
				if ($lastImportedLocalTime > $feed['last_import_localtime']) {
					$fields['last_import_localtime'] = $lastImportedLocalTime;
				}
				// Update feed title if empty
				if ($feed['title'] == '') {
					if (TYPO3_DLOG) {
						t3lib_div::devLog(sprintf('Updating title for feed with URL "%s"', $feed['url']), 'yafi');
					}
					$feed['title'] = $simplePie->get_title();
				}
				$GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_yafi_feed', 'uid=' . $feed['uid'], $fields);

				// Notify importer that feed has ended
				foreach ($importers as $importerData) {
					if (isset(self::$registeredImporters[$importerData['importer_type']])) {
						$importer = &self::$registeredImporters[$importerData['importer_type']];
						/* @var $importer tx_yafi_importer */
						$importer->importEnd();
					}
				}
			}
		}
		$GLOBALS['TYPO3_DB']->sql_free_result($res);
		return true;
	}

	/**
	 * Removes expired items. This function is not implemented yet.
	 *
	 * @param	array		$conf	Configuration array
	 * @return	void
	 */
	function removeExpiredPosts(array $conf) {
		t3lib_div::devLog('tx_yafl_api::removeExpiredPosts() is not implemented yet', 'yafi', 2);
	}

	/**
	 * Retrieves import statistics for the last import operation. Result is an array. Keys are STATS_* constants.
	 *
	 * @return	array		Import statistics
	 */
	function getImportStatistics() {
		return $this->importStats;
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/yafi/class.tx_yafi_api.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/yafi/class.tx_yafi_api.php']);
}

?>