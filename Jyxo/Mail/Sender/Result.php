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
 * Sending result.
 *
 * @category Jyxo
 * @package Jyxo_Mail
 * @subpackage Sender
 * @copyright Copyright (c) 2005-2011 Jyxo, s.r.o.
 * @license https://github.com/jyxo/php/blob/master/license.txt
 * @author Jaroslav Hanslík
 */
class Jyxo_Mail_Sender_Result extends Jyxo_Spl_Object
{
	/**
	 * Email Id.
	 *
	 * @var string
	 */
	private $messageId = '';

	/**
	 * Email source.
	 *
	 * @var string
	 */
	private $source = '';

	/**
	 * Sending time.
	 *
	 * @var Jyxo_Time_Time
	 */
	private $datetime = null;

	/**
	 * Returns email Id.
	 *
	 * @return string
	 */
	public function getMessageId()
	{
		return $this->messageId;
	}

	/**
	 * Sets email Id.
	 *
	 * @param string $messageId Email Id
	 * @return Jyxo_Mail_Sender_Result
	 */
	public function setMessageId($messageId)
	{
		$this->messageId = (string) $messageId;

		return $this;
	}

	/**
	 * Returns email source.
	 *
	 * @return string
	 */
	public function getSource()
	{
		return $this->source;
	}

	/**
	 * Sets email source.
	 *
	 * @param string $source
	 * @return Jyxo_Mail_Sender_Result
	 */
	public function setSource($source)
	{
		$this->source = (string) $source;

		return $this;
	}

	/**
	 * Returns sending time.
	 *
	 * @return Jyxo_Time_Time
	 */
	public function getDatetime()
	{
		return $this->datetime;
	}

	/**
	 * Sets sending time.
	 *
	 * @param Jyxo_Time_Time $datetime Sending time
	 * @return Jyxo_Mail_Sender_Result
	 */
	public function setDatetime(Jyxo_Time_Time $datetime)
	{
		$this->datetime = $datetime;

		return $this;
	}
}
