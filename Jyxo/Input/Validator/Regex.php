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
 * Validates a value using a regular expression.
 *
 * @category Jyxo
 * @package Jyxo_Input
 * @subpackage Validator
 * @copyright Copyright (c) 2005-2011 Jyxo, s.r.o.
 * @license https://github.com/jyxo/php/blob/master/license.txt
 * @author Jan Pěček
 */
class Jyxo_Input_Validator_Regex extends Jyxo_Input_Validator_AbstractValidator
{

	/**
	 * Regular expression.
	 *
	 * @var string
	 */
	protected $pattern;


	/**
	 * Constructor.
	 *
	 * @param string $pattern Regular expression
	 */
	public function __construct($pattern)
	{
		$this->setPattern($pattern);
	}

	/**
	 * Sets the validation regular expression.
	 *
	 * @param string $pattern Regular expression
	 * @return Jyxo_Input_Validator_Regex
	 * @throws Jyxo_Input_Validator_Exception On empty regular expression
	 */
	public function setPattern($pattern)
	{
		if (empty($pattern)) {
			throw new Jyxo_Input_Validator_Exception('Pattern could not be empty');
		}
		$this->pattern = (string) $pattern;

		return $this;
	}

	/**
	 * Returns the validation regular expression.
	 *
	 * @return string
	 */
	public function getPattern()
	{
		return $this->pattern;
	}

	/**
	 * Validates a value.
	 *
	 * @param mixed $value Input value
	 * @return boolean
	 */
	public function isValid($value)
	{
		if (!preg_match($this->getPattern(), (string) $value)) {
			return false;
		}

		return true;
	}

}
