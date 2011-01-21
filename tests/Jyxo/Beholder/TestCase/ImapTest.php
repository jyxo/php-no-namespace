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
 * Tests the Jyxo_Beholder_TestCase_Imap class.
 *
 * @see Jyxo_Beholder_TestCase_Imap
 * @copyright Copyright (c) 2005-2011 Jyxo, s.r.o.
 * @license https://github.com/jyxo/php/blob/master/license.txt
 * @author Jaroslav Hanslík
 */
class Jyxo_Beholder_TestCase_ImapTest extends Jyxo_Beholder_TestCase_DefaultTest
{
	/**
	 * Tests connection failure.
	 */
	public function testConnectionFailure()
	{
		$host = 'dummy.jyxo.com';

		$test = new Jyxo_Beholder_TestCase_Imap('Imap', $host);
		// @ on purpose
		$result = @$test->run();
		$this->assertEquals(Jyxo_Beholder_Result::FAILURE, $result->getStatus());
		$this->assertEquals(sprintf('Connection error @%s:143', $host), $result->getDescription());
	}

	/**
	 * Tests working connection.
	 */
	public function testAllOk()
	{
		// Skip the test if no IMAP connection is defined
		if ((empty($GLOBALS['imap'])) || (!preg_match('~^([^:]+):([^@]+)@([^:]+):(\d+)$~', $GLOBALS['imap'], $matches))) {
			$this->markTestSkipped('Imap not set');
		}

		list($user, $password, $host, $port) = array_slice($matches, 1);

		$test = new Jyxo_Beholder_TestCase_Imap('Imap', $host, $user, $password, $port, false);
		$result = $test->run();
		$this->assertEquals(Jyxo_Beholder_Result::SUCCESS, $result->getStatus());
		$this->assertEquals(sprintf('%s@%s:%s', $user, $host, $port), $result->getDescription());
	}
}
