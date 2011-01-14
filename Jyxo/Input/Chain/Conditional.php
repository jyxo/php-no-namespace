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
 * Chain of filters and validators for a single variable.
 * The validation itself is performed after fulfilling the condition.
 * The condition is checked by a defined validator.
 *
 * @category Jyxo
 * @package Jyxo_Input
 * @subpackage Chain
 * @copyright Copyright (c) 2005-2011 Jyxo, s.r.o.
 * @license https://github.com/jyxo/php/blob/master/license.txt
 * @author Roman Řáha
 */
class Jyxo_Input_Chain_Conditional extends Jyxo_Input_Chain
{
	/**
	 * Condition validator.
	 *
	 * @var Jyxo_Input_ValidatorInterface
	 */
	private $condValidator = null;

	/**
	 * Sets the condition validator.
	 *
	 * @param Jyxo_Input_ValidatorInterface $validator
	 */
	public function __construct(Jyxo_Input_ValidatorInterface $validator = null)
	{
		if (null !== $validator) {
			$this->setCondValidator($validator);
		}
	}

	/**
	 * Validates a value.
	 *
	 * @param mixed $value Input value
	 * @return boolean
	 */
	public function isValid($value)
	{
		// Validation is performed only if the condition is fulfilled
		if (true === $this->checkCondition($value)) {
			return parent::isValid($value);
		}
		// No validation -> the value is valid
		$this->value = $value;
		return true;
	}

	/**
	 * Checks if the condition is fulfilled.
	 *
	 * @param mixed $value Input value
	 * @return boolean
	 */
	private function checkCondition($value)
	{
		if (null === $this->condValidator) {
			// There is no validator -> always fulfilled
			return true;
		}
		return $this->condValidator->isValid($value);
	}

	/**
	 * Sets the condition validator.
	 *
	 * @param Jyxo_Input_ValidatorInterface $validator Condition validator
	 * @return Jyxo_Input_Chain_Conditional
	 */
	public function setCondValidator(Jyxo_Input_ValidatorInterface $validator)
	{
		$this->condValidator = $validator;
		return $this;
	}
}
