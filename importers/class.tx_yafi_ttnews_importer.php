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

class tx_yafi_ttnews_importer implements tx_yafi_importer {

	/**
	 * Configuration. Set by {@link importStart}
	 *
	 * @var	array
	 */
	protected $conf = false;

	const OPTION_HIDE = 1;

	const OPTION_SAVEURL = 2;

	const OPTION_REMOVEOLD = 4;

	protected $catCount;

	/**
	 * Retrieves icon of the importer. This method can return empty string if there is no icon.
	 *
	 * @return	string		Icon path, relative to SITE_path
	 * @see tx_yafi_importer::getIcon()
	 */
	function getIcon() {
		return '../' . t3lib_extMgm::siteRelPath('tt_news') . 'ext_icon.gif';
	}

	/**
	 * Retrieves importer's unique key. It is recommended to use importer's class name as a key.
	 *
	 * @return	string		Importer's unique key
	 * @see tx_yafi_importer::getKey()
	 */
	function getKey() {
		return 'tx_yafi_ttnews_importer';
	}

	/**
	 * Retrieves importer's title. This will be passed to {@link language::sL} to get readable string
	 *
	 * @return	string		Title of the importer
	 * @see tx_yafi_importer::getTitle()
	 */
	function getTitle() {
		return 'LLL:EXT:yafi/locallang_db.xml:tx_yafi_ttnews_importer';
	}

	/**
	 * Obtains flexform configuration DS as inline XML or file reference
	 *
	 * @return	string		DS or reference to DS
	 */
	function getFlexFormDS() {
		return 'FILE:EXT:yafi/importers/flexform_tx_yafi_ttnews_importer.xml';
	}

	/**
	 * Indicates that import starts for the feed.
	 *
	 * @param	tx_yafi_feed_info		$feedInfo	Feed information
	 * @param	string		$xmlConf	XML configuration
	 * @return	void
	 */
	function importStart(tx_yafi_feed_info $feedInfo, $xmlConf) {
		$conf = t3lib_div::xml2array($xmlConf);
		$this->conf = array();
		foreach ($conf['data']['sDEF']['lDEF'] as $field => $valArray) {
			$this->conf[$field] = trim($valArray['vDEF']);
		}
		if ($this->conf['archivePeriod'] == '' || ($this->conf['archivePeriod'] = strtotime($this->conf['archivePeriod'], time())) > time()) {
			$this->conf['archivePeriod'] = 0;
		}
		if ($this->conf['categories']) {
			$this->conf['categories'] = array_unique(t3lib_DB::cleanIntArray(t3lib_div::trimExplode(',', $this->conf['categories'], 0)));
		}
		else {
			$this->conf['categories'] = array();
		}
		$this->catCount = count($this->conf['categories']);
	}

	/**
	 * Imports a single item.
	 *
	 * @param	array	$feedRec	Feed record
	 * @param	tx_yafi_feed_item		$item	Item to import
	 * @return	void
	 */
	function import(array $feedRec, tx_yafi_feed_item $item) {
		$time = time();
		$subTitle = trim(strip_tags($item->getDescription()));
		if ($this->conf['cropSubheader']) {
			$subTitle = t3lib_div::fixed_lgd_cs($subTitle, $this->conf['cropSubheader']);
		}
		$fields = array(
			'pid' => $this->conf['storagePid'],
			'crdate' => $time,
			'tstamp' => $time,
			'cruser_id' => $GLOBALS['BE_USER'] ? $GLOBALS['BE_USER']->user['uid'] : 0,
			'hidden' => (0 != ($this->conf['options'] & self::OPTION_HIDE)) ? 1 : 0,
			'datetime' => $item->getDate(),
			'title' => $item->getTitle(),
			'short' => $subTitle,
			'category' => count($this->conf['categories']),
			'author' => implode(', ', $item->getAuthors()),
			'author_email' => count($item->getAuthorEmails()) == 1 ? next($item->getAuthorEmails()) : '',
			'tx_yafi_import_id' => $item->getId(),
			'tx_yafi_feed' => $feedRec['uid'],
		);
		if ($this->conf['importType'] != 0) {
			$fields['ext_url'] = $item->getURL();
			$fields['type'] = 2;
		}
		else{
			$fields['bodytext'] = $item->getContent();
			if (0 != ($this->conf['options'] & self::OPTION_SAVEURL)) {
				$fields['links'] = $item->getURL();
			}
		}
		$GLOBALS['TYPO3_DB']->exec_INSERTquery('tt_news', $fields);
		if ($this->catCount > 0) {
			$id = $GLOBALS['TYPO3_DB']->sql_insert_id();
			for ($i = 0; $i < $this->catCount; $i++) {
				$GLOBALS['TYPO3_DB']->exec_INSERTquery('tt_news_cat_mm', array(
						'uid_local' => $id,
						'uid_foreign' => $this->conf['categories'][$i],
						'tablenames' => '',
						'sorting' => $i + 1
					));
			}
		}
	}

	/**
	 * Indicates that import for a feed ended.
	 *
	 * @return	void
	 */
	function importEnd() {
		$this->archiveItems();
		$this->conf = false;
	}

	/**
	 * Archives all items whose date is before configured.
	 *
	 * @return	void
	 */
	function archiveItems() {
		if ($this->conf['archivePeriod']) {
			if (0 != ($this->conf['options'] & self::OPTION_REMOVEOLD)) {
				$fields = array('deleted' => 1);
			}
			else {
				$fields = array('archivedate' => time());
			}
			$time = intval(strtotime($this->conf['archivePeriod'], time()));
			$GLOBALS['TYPO3_DB']->exec_UPDATEquery('tt_news',
				'datetime<' . $time . ' AND pid=' . $this->conf['storagePid'] .
					' AND tx_yafi_import_id<>\'\'' .
					t3lib_BEfunc::BEenableFields('tt_news') . t3lib_BEfunc::deleteClause('tt_news'),
				$fields);
		}
	}

	/**
	 * Removes all items whose date is before $expirationTime.
	 *
	 * @param	int		$expirationTime	Expiration time
	 * @param	string		$xmlConf	XML configuration
	 * @return	void
	 */
	function removeExpiredItems($expirationTime, $xmlConf) {
		$conf = t3lib_div::xml2array($xmlConf);
		if (($pid = intval($conf['data']['data']['sDEF']['lDEF']['storagePid']['vDEF']))) {
			$GLOBALS['TYPO3_DB']->exec_UPDATEquery('tt_news',
					'datetime<' . intval($expirationTime) . ' AND pid=' . $pid .
						' AND tx_yafi_import_id<>\'\'' .
						t3lib_BEfunc::BEenableFields('tt_news') . t3lib_BEfunc::deleteClause('tt_news'),
					array('deleted' => 1));
		}
	}

	/**
	 * Checks if item is already imported
	 *
	 * @param	string	$id	Item id
	 * @return	boolean	true if imported
	 */
	function isImported($id) {
		if ($id != '' && $id != '0') {
			list($row) = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows('COUNT(*) AS t',
							'tt_news',
							'tx_yafi_import_id=' . $GLOBALS['TYPO3_DB']->fullQuoteStr($id, 'tt_news') .
							' AND pid=' . intval($this->conf['storagePid']) .
							t3lib_BEfunc::BEenableFields('tt_news') . t3lib_BEfunc::deleteClause('tt_news'));
			return ($row['t'] != 0);
		}
		return false;
	}
}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/yafi/importers/class.tx_yafi_ttnews_importer.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/yafi/importers/class.tx_yafi_ttnews_importer.php']);
}

?>