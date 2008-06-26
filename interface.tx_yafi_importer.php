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

interface tx_yafi_importer {
	/**
	 * Retrieves importer's unique key. It is recommended to use importer's class name as a key.
	 *
	 * @return	string	Importer's unique key
	 */
	function getKey();

	/**
	 * Retrieves importer's title. This will be passed to {@link language::sL} to get readable string
	 *
	 * @return	string	Title of the importer
	 */
	function getTitle();

	/**
	 * Retrieves icon of the importer. This method can return empty string if there is no icon.
	 *
	 * @return	string	Icon path, relative to typo3/ directory
	 */
	function getIcon();

	/**
	 * Obtains flexform configuration DS as inline XML or file reference
	 *
	 * @return	string	DS or reference to DS
	 */
	function getFlexFormDS();

	/**
	 * Indicates that import starts for the feed.
	 *
	 * @param	tx_yafi_feed_info	$feedInfo	Feed information
	 * @param	string	$xmlConf	XML configuration
	 * @return	void
	 */
	function importStart(tx_yafi_feed_info $feedInfo, $xmlConf);

	/**
	 * Imports a single item.
	 *
	 * @param	array	$feedRec	Feed record
	 * @param	tx_yafi_feed_item	$item	Item to import
	 * @return	void
	 */
	function import(array $feedRec, tx_yafi_feed_item $item);

	/**
	 * Indicates that import for a feed ended.
	 *
	 * @return	void
	 */
	function importEnd();

	/**
	 * Removes all items whose date is before $expirationTime.
	 *
	 * @param	int	$expirationTime	Expiration time
	 * @param	string	$xmlConf	XML configuration
	 */
	function removeExpiredItems($expirationTime, $xmlConf);

	/**
	 * Checks if item is already imported
	 *
	 * @param	string	$id	Item id
	 * @return	boolean	true if imported
	 */
	function isImported($id);
}

// XCLASS is stupid here but I have to make EM happy...
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/yafi/interface.tx_yafi_importer.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/yafi/interface.tx_yafi_importer.php']);
}

?>
