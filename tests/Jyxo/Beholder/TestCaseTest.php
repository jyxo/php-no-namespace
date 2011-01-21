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
 * Test for the Jyxo_Beholder_TestCase class.
 *
 * @see Jyxo_Beholder_TestCase
 * @copyright Copyright (c) 2005-2011 Jyxo, s.r.o.
 * @license https://github.com/jyxo/php/blob/master/license.txt
 * @author Jan Pěček
 */
class Jyxo_Beholder_TestCaseTest extends PHPUnit_Framework_TestCase
{
	/**
	 * TestCase description.
	 *
	 * @var string
	 */
	const DESCRIPTION = 'TestCase description';

	/**
	 * Tested object.
	 *
	 * @var Jyxo_Beholder_TestCase
	 */
	private $testcase;

	/**
	 * Prepares the testing environment.
	 */
	protected function setUp()
	{
		$this->testcase = $this->getMockForAbstractClass('Jyxo_Beholder_TestCase', array(self::DESCRIPTION));
	}

	/**
	 * Cleans up the testing environment.
	 */
	protected function tearDown()
	{
		$this->testcase = null;
	}

	/**
	 * Tests getting testcase description.
	 */
	public function testGetDescription()
	{
		$this->assertEquals(self::DESCRIPTION, $this->testcase->getDescription());
	}
}
