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

require_once __DIR__ . '/../../../bootstrap.php';

/**
 * Tests the Jyxo_Beholder_TestCase_Smtp class.
 *
 * @see Jyxo_Beholder_TestCase_Smtp
 * @copyright Copyright (c) 2005-2011 Jyxo, s.r.o.
 * @license https://github.com/jyxo/php/blob/master/license.txt
 * @author Jaroslav Hanslík
 */
class Jyxo_Beholder_TestCase_SmtpTest extends Jyxo_Beholder_TestCase_DefaultTest
{
	/**
	 * Tests for the sender class missing.
	 */
	public function testSmtpMissing()
	{
		// Skips the test if the class is already loaded
		if (class_exists('Jyxo_Mail_Sender_Smtp', false)) {
			$this->markTestSkipped('Jyxo_Mail_Sender_Smtp already loaded');
		}

		$test = new Jyxo_Beholder_TestCase_Smtp('Smtp', '', '');

		// Turns autoload off
		$this->disableAutoload();

		$result = $test->run();

		// Turns autoload on
		$this->enableAutoload();

		$this->assertEquals(Jyxo_Beholder_Result::NOT_APPLICABLE, $result->getStatus());
		$this->assertEquals('Class Jyxo_Mail_Sender_Smtp missing', $result->getDescription());
	}

	/**
	 * Tests for a sending failure.
	 */
	public function testSendFailure()
	{
		$test = new Jyxo_Beholder_TestCase_Smtp('Smtp', 'dummy.jyxo.com', '');
		$result = $test->run();
		$this->assertEquals(Jyxo_Beholder_Result::FAILURE, $result->getStatus());
		$this->assertEquals('Send error dummy.jyxo.com', $result->getDescription());
	}

	/**
	 * Tests for a successful sending.
	 */
	public function testSendOk()
	{
		// Skips the test if no SMTP connection is defined
		if (empty($GLOBALS['smtp'])) {
			$this->markTestSkipped('Smtp host not set');
		}

		$test = new Jyxo_Beholder_TestCase_Smtp('Smtp', $GLOBALS['smtp'], 'blog-noreply@blog.cz');
		$result = $test->run();
		$this->assertEquals(Jyxo_Beholder_Result::SUCCESS, $result->getStatus());
		$this->assertEquals($GLOBALS['smtp'], $result->getDescription());
	}
}
