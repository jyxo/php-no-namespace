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
 * All tests suite for Jyxo_Spl
 *
 * @copyright Copyright (c) 2005-2011 Jyxo, s.r.o.
 * @license https://github.com/jyxo/php/blob/master/license.txt
 * @author Jakub Tománek
 * @author Jaroslav Hanslík
 * @author Ondřej Nešpor
 */
class Jyxo_Spl_AllTests
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
		$suite = new PHPUnit_Framework_TestSuite('Jyxo SPL');

		$suite->addTestSuite('Jyxo_Spl_ArrayUtilTest');
		$suite->addTestSuite('Jyxo_Spl_CountableLimitIteratorTest');
		$suite->addTestSuite('Jyxo_Spl_FilterIteratorTest');
		$suite->addTestSuite('Jyxo_Spl_MapIteratorTest');
		$suite->addTestSuite('Jyxo_Spl_ObjectTest');
		$suite->addTestSuite('Jyxo_Spl_ObjectCacheTest');

		return $suite;
	}
}
