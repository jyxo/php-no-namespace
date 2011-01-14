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
 * Validates (Czech) Tax ID.
 *
 * @category Jyxo
 * @package Jyxo_Input
 * @subpackage Validator
 * @copyright Copyright (c) 2005-2011 Jyxo, s.r.o.
 * @license https://github.com/jyxo/php/blob/master/license.txt
 * @author Jaroslav Hanslík
 */
class Jyxo_Input_Validator_IsTaxId extends Jyxo_Input_Validator_AbstractValidator
{
	/**
	 * Strict check.
	 *
	 * If turned on, validity of IČ/birth number is also performed. If not, only length is checked.
	 *
	 * @var boolean
	 */
	private $strict = true;

	/**
	 * Constructor.
	 *
	 * @param boolean $strict Turns strict checking on or off.
	 */
	public function __construct($strict = true)
	{
		$this->strict = (bool) $strict;
	}

	/**
	 * Validates a value.
	 *
	 * @param mixed $value Input value
	 * @return boolean
	 */
	public function isValid($value)
	{
		// Removes spaces
		$taxId = preg_replace('~\s+~', '', (string) $value);

		$sub = '';
		// New Tax ID format since 1st May 2004
		if (preg_match('~^CZ(\d{8,10})$~', $taxId, $matches)) {
			$sub = $matches[1];
			// But to be sure we try the old one as well
		} elseif (preg_match('~^\d{3}-(\d{8,10})$~', $taxId, $matches)) {
			$sub = $matches[1];
		}
		if (!empty($sub)) {
			// Strict checking off - allows the so called "own numbers"
			if (!$this->strict) {
				return true;
			}

			// Checks if it is a valid IČ
			if (Jyxo_Input_Validator_IsCompanyId::validate($sub)) {
				return true;
			}

			// Checks if it is a valid birth number
			if (Jyxo_Input_Validator_IsBirthNumber::validate($sub)) {
				return true;
			}
		}

		return false;
	}
}
