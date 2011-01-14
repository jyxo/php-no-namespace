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
 * Email address.
 *
 * @category Jyxo
 * @package Jyxo_Mail
 * @subpackage Email
 * @copyright Copyright (c) 2005-2011 Jyxo, s.r.o.
 * @license https://github.com/jyxo/php/blob/master/license.txt
 * @author Jaroslav Hanslík
 */
class Jyxo_Mail_Email_Address extends Jyxo_Spl_Object
{
	/**
	 * Email address.
	 *
	 * @var string
	 */
	private $email = '';

	/**
	 * Name.
	 *
	 * @var string
	 */
	private $name = '';

	/**
	 * Creates an address.
	 *
	 * @param string $email Email
	 * @param string $name Name
	 * @throws InvalidArgumentException If an invalid email address was provided
	 */
	public function __construct($email, $name = '')
	{
		$this->setEmail($email);
		$this->setName($name);
	}

	/**
	 * Returns email address.
	 *
	 * @return string
	 */
	public function getEmail()
	{
		return $this->email;
	}

	/**
	 * Sets email address.
	 *
	 * @param string $email Email address
	 * @return Jyxo_Mail_Email_Address
	 * @throws InvalidArgumentException If an invalid email address was provided
	 */
	public function setEmail($email)
	{
		$email = trim((string) $email);

		// Validity check
		if (!Jyxo_Input_Validator_IsEmail::validate($email)) {
			throw new InvalidArgumentException(sprintf('Invalid email address %s.', $email));
		}

		$this->email = $email;

		return $this;
	}

	/**
	 * Returns name.
	 *
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Sets name.
	 *
	 * @param string $name Name
	 * @return Jyxo_Mail_Email_Address
	 */
	public function setName($name)
	{
		$this->name = trim((string) $name);

		return $this;
	}
}
