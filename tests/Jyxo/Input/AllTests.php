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

require_once __DIR__ . '/../../bootstrap.php';

/**
 * All tests suite of Jyxo_Input.
 *
 * @copyright Copyright (c) 2005-2011 Jyxo, s.r.o.
 * @license https://github.com/jyxo/php/blob/master/license.txt
 * @author Jakub Tománek
 */
class Jyxo_Input_AllTests
{
	/**
	 * Runs testing.
	 */
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run(self::suite());
	}

	/**
	 * Creates the test suite.
	 *
	 * @return PHPUnit_Framework_TestSuite
	 */
	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite('Jyxo Input');

		$suite->addTestSuite('Jyxo_Input_FactoryTest');
		$suite->addTestSuite('Jyxo_Input_FilterTest');
		$suite->addTestSuite('Jyxo_Input_FluentTest');
		$suite->addTestSuite('Jyxo_Input_ValidatorTest');

		$suite->addTestSuite('Jyxo_Input_Chain_ConditionalTest');

		$suite->addTestSuite('Jyxo_Input_Validator_StringLengthBetweenTest');

		return $suite;
	}
}
