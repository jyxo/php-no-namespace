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
 * Tests the Jyxo_Beholder_TestCase_PhpExtension class.
 *
 * @see Jyxo_Beholder_TestCase_PhpExtension
 * @copyright Copyright (c) 2005-2011 Jyxo, s.r.o.
 * @license https://github.com/jyxo/php/blob/master/license.txt
 * @author Jaroslav Hanslík
 */
class Jyxo_Beholder_TestCase_PhpExtensionTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Tests some extensions missing.
	 */
	public function testMissing()
	{
		$test = new Jyxo_Beholder_TestCase_PhpExtension('Extensions', array('pcre', 'runkit', 'parsekit'));
		$result = $test->run();
		$this->assertEquals(Jyxo_Beholder_Result::FAILURE, $result->getStatus());
		$this->assertEquals('Missing runkit, parsekit', $result->getDescription());
	}

	/**
	 * Tests all requested extension present.
	 */
	public function testAvailable()
	{
		$test = new Jyxo_Beholder_TestCase_PhpExtension('Extensions', array('pcre', 'spl', 'reflection'));
		$result = $test->run();
		$this->assertEquals(Jyxo_Beholder_Result::SUCCESS, $result->getStatus());
		$this->assertEquals('OK', $result->getDescription());
	}
}
