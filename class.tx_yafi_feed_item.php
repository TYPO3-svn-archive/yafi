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
 *
 */
class tx_yafi_feed_item {

	protected $id;

	protected $url;

	protected $authors = array();

	protected $author_emails = array();

	protected $date;

	protected $title;

	protected $description;

	protected $content;

	/**
	 * Creates an instance of this class. Note that this is considered to be an implementation detail
	 * and may change at any moment (including parameters list). Overriding classes should expect the use
	 * of {@link t3lib_div::makeInstanceClassName} for this class and a call to constructor with
	 * the same set of parameters as in this class.
	 *
	 * @param	SimplePie_Item		$item
	 * @return	void
	 */
	public function __construct(SimplePie_Item $item) {
		$this->id = $item->get_id();
		$this->url = $item->get_permalink();
		$this->date = strtotime($item->get_date());
		$this->title = $item->get_title();
		$this->description = $item->get_description();
		$this->content = $item->get_content();
		$authors = $item->get_authors();;
		if (is_array($authors)) {
			foreach ($authors as $author) {
				/* @var $author SimplePie_Author */
				$this->authors[] = $author->get_name();
				$this->author_emails[] = $author->get_email();
			}
		}
	}

	/**
	 * Retrieves feed item id
	 *
	 * @return	int	ID of the item
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * Retrieves URL of the item
	 *
	 * @return	string	URL of the item
	 */
	public function getURL() {
		return $this->url;
	}

	/**
	 * Retrieves authors of the item
	 *
	 * @return	array	Author names
	 */
	public function getAuthors() {
		return $this->authors;
	}

	/**
	 * Retrieves author e-mails
	 *
	 * @return	array	Author e-mails (in the same order as author names)
	 */
	public function getAuthorEmails() {
		return $this->authorEmails;
	}

	/**
	 * Retrieves item date and time as Unix time stamp
	 *
	 * @return	int	Item date and time
	 */
	public function getDate() {
		return $this->date;
	}

	/**
	 * Retrieves item title
	 *
	 * @return	string	Item title
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * Retrieves item description (usually short summary of the article). It
	 * may contain html tags or html entities. It is recommended to strip
	 * tags/entities from description before importing them.
	 *
	 * @return	string	Description
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * Retrieves item content.
	 *
	 * @return	string	Item content
	 */
	public function getContent() {
		return $this->content;
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/yafi/class.tx_yafi_feed_item.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/yafi/class.tx_yafi_feed_item.php']);
}

?>
