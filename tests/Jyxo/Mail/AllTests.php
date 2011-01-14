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
 * Test suite for Jyxo_Mail.
 *
 * @copyright Copyright (c) 2005-2011 Jyxo, s.r.o.
 * @license https://github.com/jyxo/php/blob/master/license.txt
 * @author Jaroslav Hanslík
 */
class Jyxo_Mail_AllTests
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
		$suite = new PHPUnit_Framework_TestSuite('Jyxo Mail');

		$suite->addTestSuite('Jyxo_Mail_Email_Attachment_FileTest');
		$suite->addTestSuite('Jyxo_Mail_Email_Attachment_InlineFileTest');
		$suite->addTestSuite('Jyxo_Mail_Email_Attachment_InlineStringTest');
		$suite->addTestSuite('Jyxo_Mail_Email_Attachment_StringTest');
		$suite->addTestSuite('Jyxo_Mail_Email_AddressTest');
		$suite->addTestSuite('Jyxo_Mail_Email_BodyTest');
		$suite->addTestSuite('Jyxo_Mail_Email_HeaderTest');
		$suite->addTestSuite('Jyxo_Mail_EncodingTest');
		$suite->addTestSuite('Jyxo_Mail_EmailTest');
		$suite->addTestSuite('Jyxo_Mail_SenderTest');

		return $suite;
	}
}
