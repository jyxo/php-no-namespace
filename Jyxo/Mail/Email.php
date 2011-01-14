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
 * Email contents container.
 *
 * @category Jyxo
 * @package Jyxo_Mail
 * @subpackage Email
 * @copyright Copyright (c) 2005-2011 Jyxo, s.r.o.
 * @license https://github.com/jyxo/php/blob/master/license.txt
 * @author Jaroslav Hanslík
 */
class Jyxo_Mail_Email extends Jyxo_Spl_Object
{
	/**
	 * Highest priority.
	 *
	 * @var integer
	 */
	const PRIORITY_HIGHEST = 1;

	/**
	 * High priority.
	 *
	 * @var integer
	 */
	const PRIORITY_HIGH = 2;

	/**
	 * Normal priority.
	 *
	 * @var integer
	 */
	const PRIORITY_NORMAL = 3;

	/**
	 * Low priority.
	 *
	 * @var integer
	 */
	const PRIORITY_LOW = 4;

	/**
	 * Lowest priority.
	 *
	 * @var integer
	 */
	const PRIORITY_LOWEST = 5;

	/**
	 * Subject.
	 *
	 * @var string
	 */
	private $subject = '';

	/**
	 * Email sender.
	 *
	 * @var Jyxo_Mail_Email_Address
	 */
	private $from = null;

	/**
	 * List of recipients.
	 *
	 * @var array
	 */
	private $to = array();

	/**
	 * List of carbon copy recipients.
	 *
	 * @var array
	 */
	private $cc = array();

	/**
	 * List of blind carbon copy recipients.
	 *
	 * @var array
	 */
	private $bcc = array();

	/**
	 * Response recipient address.
	 *
	 * @var array
	 */
	private $replyTo = array();

	/**
	 * Reading confirmation recipient.
	 *
	 * @var Jyxo_Mail_Email_Address
	 */
	private $confirmReadingTo = null;

	/**
	 * Id of the message this is a response to.
	 *
	 * @var string
	 */
	private $inReplyTo = '';

	/**
	 * References to previous messages in the thread.
	 *
	 * @var array
	 */
	private $references = array();

	/**
	 * Message priority.
	 *
	 * @var integer
	 */
	private $priority = 0;

	/**
	 * List of custom headers.
	 *
	 * @var array
	 */
	private $headers = array();

	/**
	 * Message body.
	 *
	 * @var Jyxo_Mail_Email_Body
	 */
	private $body = null;

	/**
	 * List of attachments.
	 *
	 * @var array
	 */
	private $attachments = array();

	/**
	 * Returns subject.
	 *
	 * @return string
	 */
	public function getSubject()
	{
		return $this->subject;
	}

	/**
	 * Sets subject.
	 *
	 * @param string $subject Subject
	 * @return Jyxo_Mail_Email
	 */
	public function setSubject($subject)
	{
		$this->subject = (string) $subject;

		return $this;
	}

	/**
	 * Returns sender address.
	 *
	 * @return Jyxo_Mail_Email_Address
	 */
	public function getFrom()
	{
		return $this->from;
	}

	/**
	 * Sets sender address.
	 *
	 * @param Jyxo_Mail_Email_Address $from Message sender
	 * @return Jyxo_Mail_Email
	 */
	public function setFrom(Jyxo_Mail_Email_Address $from)
	{
		$this->from = $from;

		return $this;
	}

	/**
	 * Returns list of message recipients.
	 *
	 * @return array
	 */
	public function getTo()
	{
		return $this->to;
	}

	/**
	 * Adds a recipient.
	 *
	 * @param Jyxo_Mail_Email_Address $to New recipient
	 * @return Jyxo_Mail_Email
	 */
	public function addTo(Jyxo_Mail_Email_Address $to)
	{
		$this->to[] = $to;

		return $this;
	}

	/**
	 * Returns list of carbon copy recipients.
	 *
	 * @return array
	 */
	public function getCc()
	{
		return $this->cc;
	}

	/**
	 * Adds a carbon copy recipient.
	 *
	 * @param Jyxo_Mail_Email_Address $cc New recipient
	 * @return Jyxo_Mail_Email
	 */
	public function addCc(Jyxo_Mail_Email_Address $cc)
	{
		$this->cc[] = $cc;

		return $this;
	}

	/**
	 * Returns list of blind carbon copy recipients.
	 *
	 * @return array
	 */
	public function getBcc()
	{
		return $this->bcc;
	}

	/**
	 * Adds a blind carbon copy recipient.
	 *
	 * @param Jyxo_Mail_Email_Address $bcc New recipient
	 * @return Jyxo_Mail_Email
	 */
	public function addBcc(Jyxo_Mail_Email_Address $bcc)
	{
		$this->bcc[] = $bcc;

		return $this;
	}

	/**
	 * Returns the 'ReplyTo' address.
	 *
	 * @return array
	 */
	public function getReplyTo()
	{
		return $this->replyTo;
	}

	/**
	 * Adds a 'ReplyTo' address.
	 *
	 * @param Jyxo_Mail_Email_Address $replyTo
	 * @return Jyxo_Mail_Email
	 */
	public function addReplyTo(Jyxo_Mail_Email_Address $replyTo)
	{
		$this->replyTo[] = $replyTo;

		return $this;
	}

	/**
	 * Returns a reading confirmation address.
	 *
	 * @return array
	 */
	public function getConfirmReadingTo()
	{
		return $this->confirmReadingTo;
	}

	/**
	 * Sets a reading confirmation address.
	 *
	 * @param Jyxo_Mail_Email_Address $confirmReadingTo Confirmation recipient
	 * @return Jyxo_Mail_Email
	 */
	public function setConfirmReadingTo(Jyxo_Mail_Email_Address $confirmReadingTo)
	{
		$this->confirmReadingTo = $confirmReadingTo;

		return $this;
	}

	/**
	 * Sets Id of the message this is a response to.
	 *
	 * @param string $inReplyTo Message Id
	 * @param array $references Previous mail references
	 * @return Jyxo_Mail_Email
	 */
	public function setInReplyTo($inReplyTo, array $references = array())
	{
		$this->inReplyTo = (string) $inReplyTo;
		$this->references = $references;

		return $this;
	}

	/**
	 * Returns Id of the message this is a response to.
	 *
	 * @return string
	 */
	public function getInReplyTo()
	{
		return $this->inReplyTo;
	}

	/**
	 * Returns references to previous messages in the thread.
	 *
	 * @return array
	 */
	public function getReferences()
	{
		return $this->references;
	}

	/**
	 * Returns message priority.
	 *
	 * @return integer
	 */
	public function getPriority()
	{
		return $this->priority;
	}

	/**
	 * Sets message priority.
	 *
	 * @param integer $priority Priority
	 * @return Jyxo_Mail_Email
	 * @throws InvalidArgumentException If an unknown priority was provided
	 */
	public function setPriority($priority)
	{
		static $priorities = array(
			self::PRIORITY_HIGHEST => true,
			self::PRIORITY_HIGH => true,
			self::PRIORITY_NORMAL => true,
			self::PRIORITY_LOW => true,
			self::PRIORITY_LOWEST => true
		);
		if (!isset($priorities[$priority])) {
			throw new InvalidArgumentException(sprintf('Unknown priority %s', $priority));
		}

		$this->priority = (int) $priority;

		return $this;
	}

	/**
	 * Returns custom headers.
	 *
	 * @return array
	 */
	public function getHeaders()
	{
		return $this->headers;
	}

	/**
	 * Adds a custom header.
	 *
	 * @param Jyxo_Mail_Email_Header $header Header
	 * @return Jyxo_Mail_Email
	 */
	public function addHeader(Jyxo_Mail_Email_Header $header)
	{
		$this->headers[] = $header;

		return $this;
	}

	/**
	 * Returns message body.
	 *
	 * @return Jyxo_Mail_Email_Body
	 */
	public function getBody()
	{
		return $this->body;
	}

	/**
	 * Sets message body.
	 *
	 * @param Jyxo_Mail_Email_Body $body Body
	 * @return Jyxo_Mail_Email
	 */
	public function setBody(Jyxo_Mail_Email_Body $body)
	{
		$this->body = $body;

		return $this;
	}

	/**
	 * Returns attachments.
	 *
	 * @return array
	 */
	public function getAttachments()
	{
		return $this->attachments;
	}

	/**
	 * Adds an attachment.
	 *
	 * @param Jyxo_Mail_Email_Attachment $attachment Attachment
	 * @return Jyxo_Mail_Email
	 */
	public function addAttachment(Jyxo_Mail_Email_Attachment $attachment)
	{
		$this->attachments[] = $attachment;

		return $this;
	}

	/**
	 * Checks if the message contains any attachments.
	 *
	 * @return boolean
	 */
	public function hasInlineAttachments()
	{
		foreach ($this->attachments as $attachment) {
			if ($attachment->isInline()) {
				return true;
			}
		}

		return false;
	}
}
