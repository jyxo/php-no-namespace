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
 * Test for the Jyxo_Beholder_Result class.
 *
 * @see Jyxo_Beholder_Result
 * @copyright Copyright (c) 2005-2011 Jyxo, s.r.o.
 * @license https://github.com/jyxo/php/blob/master/license.txt
 * @author Jaroslav Hanslík
 */
class Jyxo_Beholder_ResultTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Tests success results.
	 */
	public function testSuccess()
	{
		// No label
		$result = new Jyxo_Beholder_Result(Jyxo_Beholder_Result::SUCCESS);
		$this->assertTrue($result->isSuccess());
		$this->assertEquals(Jyxo_Beholder_Result::SUCCESS, $result->getStatus());
		$this->assertEquals($result->getStatusMessage(), $result->getDescription());

		// With label
		$result = new Jyxo_Beholder_Result(Jyxo_Beholder_Result::SUCCESS, 'Desc');
		$this->assertEquals('Desc', $result->getDescription());
	}

	/**
	 * Tests failure results.
	 */
	public function testFailure()
	{
		// No label
		$result = new Jyxo_Beholder_Result(Jyxo_Beholder_Result::FAILURE);
		$this->assertTrue(!$result->isSuccess());
		$this->assertEquals(Jyxo_Beholder_Result::FAILURE, $result->getStatus());
		$this->assertEquals($result->getStatusMessage(), $result->getDescription());

		// With label
		$result = new Jyxo_Beholder_Result(Jyxo_Beholder_Result::FAILURE, 'Desc');
		$this->assertEquals('Desc', $result->getDescription());
	}

	/**
	 * Tests not applicable results.
	 */
	public function testNotApplicable()
	{
		// No label
		$result = new Jyxo_Beholder_Result(Jyxo_Beholder_Result::NOT_APPLICABLE);
		$this->assertTrue($result->isSuccess());
		$this->assertEquals(Jyxo_Beholder_Result::NOT_APPLICABLE, $result->getStatus());
		$this->assertEquals($result->getStatusMessage(), $result->getDescription());

		// With label
		$result = new Jyxo_Beholder_Result(Jyxo_Beholder_Result::NOT_APPLICABLE, 'Desc');
		$this->assertEquals('Desc', $result->getDescription());
	}

	/**
	 * Tests the exception thrown on invalid result type.
	 */
	public function testInvalidStatus()
	{
		$this->setExpectedException('InvalidArgumentException');
		$result = new Jyxo_Beholder_Result('dummy');
	}
}
