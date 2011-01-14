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
 * Base abstract validator class.
 *
 * @category Jyxo
 * @package Jyxo_Input
 * @subpackage Validator
 * @copyright Copyright (c) 2005-2011 Jyxo, s.r.o.
 * @license https://github.com/jyxo/php/blob/master/license.txt
 * @author Jaroslav Hanslík
 */
abstract class Jyxo_Input_Validator_AbstractValidator implements Jyxo_Input_ValidatorInterface
{
	/**
	 * Constructor.
	 */
	public function __construct()
	{}

	/**
	 * Validates a value.
	 *
	 * @param mixed $value Input value
	 * @return boolean
	 */
	public static function validate($value)
	{
		$class = new ReflectionClass(get_called_class());
		$validator = $class->newInstanceArgs(array_slice(func_get_args(), 1));
		return $validator->isValid($value);
	}
}
