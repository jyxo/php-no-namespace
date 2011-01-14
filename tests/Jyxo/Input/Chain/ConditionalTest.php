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
 * Test of conditional validator Jyxo_Input_Chain_Conditional
 *
 * @copyright Copyright (c) 2005-2011 Jyxo, s.r.o.
 * @license https://github.com/jyxo/php/blob/master/license.txt
 * @author Jan Pěček
 */
class Jyxo_Input_Chain_ConditionalTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Tests no condition.
	 */
	public function testNullCondition()
	{
		// No validator -> true for any value
		$validator = new Jyxo_Input_Chain_Conditional();
		foreach (array('example', 42, array(), new stdClass(), 1.23, true, false) as $value) {
			$this->assertTrue($validator->isValid($value));
		}
	}

	/**
	 * Tests for conditional validation (is executed only if the condition is fulfilled)
	 */
	public function testCondition()
	{
		static $value = 42;
		$validator = new Jyxo_Input_Chain_Conditional(new Jyxo_Input_Validator_IsInt());
		$validator->addValidator(new Jyxo_Input_Validator_LessThan($value));
		$good = array(
			$value - 1,
			(int) ($value / 2),
			sqrt($value),
			'example',
			array(),
			false,
			true
		);
		$bad = array(
			$value * 2,
			(string) ($value * 2),
			(float) ($value * 2)
		);

		foreach ($good as $value) {
			$this->assertTrue(
				$validator->isValid($value),
				sprintf('Test of value %s should be true but is false.', $value)
			);
		}
		foreach ($bad as $value) {
			$this->assertFalse(
				$validator->isValid($value),
				sprintf('Test of value %s should be false but is true.', $value)
			);
		}
	}
}
