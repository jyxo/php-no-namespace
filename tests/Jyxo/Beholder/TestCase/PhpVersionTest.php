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
 * Tests the Jyxo_Beholder_TestCase_PhpVersion class.
 *
 * @see Jyxo_Beholder_TestCase_PhpVersion
 * @copyright Copyright (c) 2005-2011 Jyxo, s.r.o.
 * @license https://github.com/jyxo/php/blob/master/license.txt
 * @author Jaroslav Hanslík
 */
class Jyxo_Beholder_TestCase_PhpVersionTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Tests PHP version not matching.
	 */
	public function testPhpVersionWrong()
	{
		$test = new Jyxo_Beholder_TestCase_PhpVersion('Version', '5.2');
		$result = $test->run();
		$this->assertEquals(Jyxo_Beholder_Result::FAILURE, $result->getStatus());
		$this->assertEquals(sprintf('Version %s, expected = %s', phpversion(), '5.2'), $result->getDescription());
	}

	/**
	 * Tests PHP version being greater than required.
	 */
	public function testPhpVersionGreaterThan()
	{
		$test = new Jyxo_Beholder_TestCase_PhpVersion('Version', '5.2', '', '>=');
		$result = $test->run();
		$this->assertEquals(Jyxo_Beholder_Result::SUCCESS, $result->getStatus());
		$this->assertEquals(sprintf('Version %s', phpversion()), $result->getDescription());
	}

	/**
	 * Tests PHP version being equal to required.
	 */
	public function testPhpVersionEquals()
	{
		$test = new Jyxo_Beholder_TestCase_PhpVersion('Version', phpversion());
		$result = $test->run();
		$this->assertEquals(Jyxo_Beholder_Result::SUCCESS, $result->getStatus());
		$this->assertEquals(sprintf('Version %s', phpversion()), $result->getDescription());
	}

	/**
	 * Tests PHP version being wrong.
	 */
	public function testExtensionVersionWrong()
	{
		$test = new Jyxo_Beholder_TestCase_PhpVersion('Version', '3.0', 'core');
		$result = $test->run();
		$this->assertEquals(Jyxo_Beholder_Result::FAILURE, $result->getStatus());
		$this->assertEquals(sprintf('Version %s, expected = %s', phpversion('core'), '3.0'), $result->getDescription());
	}

	/**
	 * Tests extension version being greater than required.
	 */
	public function testExtensionVersionGreaterThan()
	{
		$test = new Jyxo_Beholder_TestCase_PhpVersion('Version', '3.0', 'core', '>=');
		$result = $test->run();
		$this->assertEquals(Jyxo_Beholder_Result::SUCCESS, $result->getStatus());
		$this->assertEquals(sprintf('Version %s', phpversion('core')), $result->getDescription());
	}

	/**
	 * Tests extension version being equal to required.
	 */
	public function testExtensionVersionEquals()
	{
		$test = new Jyxo_Beholder_TestCase_PhpVersion('Version', phpversion('core'), 'core');
		$result = $test->run();
		$this->assertEquals(Jyxo_Beholder_Result::SUCCESS, $result->getStatus());
		$this->assertEquals(sprintf('Version %s', phpversion('core')), $result->getDescription());
	}

	/**
	 * Tests missing extensions.
	 */
	public function testExtensionMissing()
	{
		$test = new Jyxo_Beholder_TestCase_PhpVersion('Version', '1.0', 'runkit');
		$result = $test->run();
		$this->assertEquals(Jyxo_Beholder_Result::NOT_APPLICABLE, $result->getStatus());
		$this->assertEquals('Extension runkit missing', $result->getDescription());
	}
}
