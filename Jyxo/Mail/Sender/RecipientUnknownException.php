<?php

/**
 * Jyxo PHP Library
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file license.txt.
 * It is also available through the world-wide-web at this URL:
 * https://github.com/jyxo/php/blob/master/license.txt
 */

/**
 * Exception used when some recipients' addresses do not exist.
 *
 * @category Jyxo
 * @package Jyxo_Mail
 * @subpackage Sender
 * @copyright Copyright (c) 2005-2011 Jyxo, s.r.o.
 * @license https://github.com/jyxo/php/blob/master/license.txt
 * @author Jaroslav Hanslík
 */
class Jyxo_Mail_Sender_RecipientUnknownException extends Jyxo_Mail_Sender_Exception
{
	/**
	 * List of non-existent addresses.
	 *
	 * @var array
	 */
	private $list = array();

	/**
	 * Creates an exception.
	 *
	 * @param string $message Exception message
	 * @param integer $code Exception code
	 * @param array $list List of non-existent addresses
	 */
	public function __construct($message = null, $code = 0, array $list = array())
	{
		parent::__construct($message, $code);
		$this->list = $list;
	}

	/**
	 * Returns the list of non-existent addresses.
	 *
	 * @return array
	 */
	public function getList()
	{
		return $this->list;
	}
}
