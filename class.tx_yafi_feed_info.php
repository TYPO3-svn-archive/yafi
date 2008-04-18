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

class tx_yafi_feed_info {

	/** Feed URL */
	protected $feedUrl;

	/**
	 * Creates an instance of this class. Note that this is considered to be an implementation detail
	 * and may change at any moment (including parameters list). Overriding classes should expect the use
	 * of {@link t3lib_div::makeInstanceClassName} for this class and a call to constructor with
	 * the same set of parameters as in this class.
	 *
	 * @param	SimplePie_Item	$item
	 */
	function __construct($feedUrl) {
		$this->feedUrl = $feedUrl;
	}

	function getFeedURL() {
		return $this->feedUrl;
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/yafi/class.tx_yafi_feed_info.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/yafi/class.tx_yafi_feed_info.php']);
}

?>
