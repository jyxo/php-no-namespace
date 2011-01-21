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
 * Tests suite for Jyxo_Beholder.
 *
 * @copyright Copyright (c) 2005-2011 Jyxo, s.r.o.
 * @license https://github.com/jyxo/php/blob/master/license.txt
 * @author Jaroslav Hanslík
 */
class Jyxo_Beholder_AllTests
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
		$suite = new PHPUnit_Framework_TestSuite('Jyxo Beholder');

		$suite->addTestSuite('Jyxo_Beholder_ResultTest');
		$suite->addTestSuite('Jyxo_Beholder_TestCase_FileSystemTest');
		$suite->addTestSuite('Jyxo_Beholder_TestCase_ImapTest');
		$suite->addTestSuite('Jyxo_Beholder_TestCase_MemcachedTest');
		$suite->addTestSuite('Jyxo_Beholder_TestCase_PgsqlTest');
		$suite->addTestSuite('Jyxo_Beholder_TestCase_PhpExtensionTest');
		$suite->addTestSuite('Jyxo_Beholder_TestCase_PhpVersionTest');
		$suite->addTestSuite('Jyxo_Beholder_TestCase_PhpZendTest');
		$suite->addTestSuite('Jyxo_Beholder_TestCase_SmtpTest');
		$suite->addTestSuite('Jyxo_Beholder_TestCaseTest');

		return $suite;
	}
}
