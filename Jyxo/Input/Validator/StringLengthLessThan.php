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
 * Validates string length to be lower than the given length.
 *
 * @category Jyxo
 * @package Jyxo_Input
 * @subpackage Validator
 * @copyright Copyright (c) 2005-2011 Jyxo, s.r.o.
 * @license https://github.com/jyxo/php/blob/master/license.txt
 * @author Jan Pěček
 */
class Jyxo_Input_Validator_StringLengthLessThan extends Jyxo_Input_Validator_AbstractValidator
{
	/**
	 * Maximal string length.
	 *
	 * @var integer
	 */
	protected $max;

	/**
	 * Constructor.
	 *
	 * @param integer $max Maximal string length (value length must be lower)
	 */
	public function __construct($max)
	{
		$this->setMax($max);
	}

	/**
	 * Sets the maximal string length.
	 *
	 * @param integer $max Maximal string length
	 * @return Jyxo_Input_Validator_StringLengthLessThan
	 * @throws InvalidArgumentException If the maximal length is negative or zero
	 */
	public function setMax($max)
	{
		$max = (int) $max;

		if ($max <= 0) {
			throw new InvalidArgumentException('Length of string must be greater than zero.');
		}

		$this->max = $max;

		return $this;
	}

	/**
	 * Returns the maximal string length.
	 *
	 * @return integer
	 */
	public function getMax()
	{
		return $this->max;
	}

	/**
	 * Validates a value.
	 *
	 * @param mixed $value Input value
	 * @return boolean
	 */
	public function isValid($value)
	{
		return mb_strlen((string) $value, 'utf-8') < $this->getMax();
	}
}
