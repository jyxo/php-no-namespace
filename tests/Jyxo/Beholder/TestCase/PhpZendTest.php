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
 * Tests the Jyxo_Beholder_TestCase_PhpZend class.
 *
 * @see Jyxo_Beholder_TestCase_PhpZend
 * @copyright Copyright (c) 2005-2011 Jyxo, s.r.o.
 * @license https://github.com/jyxo/php/blob/master/license.txt
 * @author Jaroslav Hanslík
 */
class Jyxo_Beholder_TestCase_PhpZendTest extends Jyxo_Beholder_TestCase_DefaultTest
{
	/**
	 * Tests Zend Framwork missing.
	 */
	public function testMissing()
	{
		// Skip if the Zend_Version class is already loaded
		if (class_exists('Zend_Version', false)) {
			$this->markTestSkipped('Zend_Version already loaded');
		}

		$test = new Jyxo_Beholder_TestCase_PhpZend('Zend');

		// Turn autoload off
		$this->disableAutoload();

		$result = $test->run();

		// Turn autoload on
		$this->enableAutoload();

		$this->assertEquals(Jyxo_Beholder_Result::FAILURE, $result->getStatus());
		$this->assertEquals('Zend framework missing', $result->getDescription());
	}

	/**
	 * Tests Zend Framework availability.
	 */
	public function testAvailable()
	{
		$test = new Jyxo_Beholder_TestCase_PhpZend('Zend');
		$result = $test->run();
		$this->assertEquals(Jyxo_Beholder_Result::SUCCESS, $result->getStatus());
		$this->assertEquals(sprintf('Version %s', Zend_Version::VERSION), $result->getDescription());
	}

	/**
	 * Tests exact Zend Framwork version.
	 */
	public function testEqualVersion()
	{
		$test = new Jyxo_Beholder_TestCase_PhpZend('Zend', Zend_Version::VERSION);
		$result = $test->run();

		$this->assertEquals(Jyxo_Beholder_Result::SUCCESS, $result->getStatus());
		$this->assertEquals(sprintf('Version %s, expected = %s', Zend_Version::VERSION, Zend_Version::VERSION), $result->getDescription());
	}

	/**
	 * Tests for a lower Zend Framwork version.
	 */
	public function testLesserVersion()
	{
		$test = new Jyxo_Beholder_TestCase_PhpZend('Zend', '0.9', '>');
		$result = $test->run();

		$this->assertEquals(Jyxo_Beholder_Result::SUCCESS, $result->getStatus());
		$this->assertEquals(sprintf('Version %s, expected > 0.9', Zend_Version::VERSION), $result->getDescription());
	}

	/**
	 * Tests for a higher Zend Framwork version.
	 */
	public function testHigherVersion()
	{
		$test = new Jyxo_Beholder_TestCase_PhpZend('Zend', '2.0', '<');
		$result = $test->run();

		$this->assertEquals(Jyxo_Beholder_Result::SUCCESS, $result->getStatus());
		$this->assertEquals(sprintf('Version %s, expected < 2.0', Zend_Version::VERSION), $result->getDescription());
	}

	/**
	 * Tests for a wrong Zend Framwork version.
	 */
	public function testWrongVersion()
	{
		$test = new Jyxo_Beholder_TestCase_PhpZend('Zend', '1.9.0');
		$result = $test->run();
		$this->assertEquals(Jyxo_Beholder_Result::FAILURE, $result->getStatus());
		$this->assertEquals(sprintf('Version %s, expected = 1.9.0', Zend_Version::VERSION), $result->getDescription());
	}
}
