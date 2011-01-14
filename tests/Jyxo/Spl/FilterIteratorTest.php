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
 * Test for class Jyxo_Spl_FilterIterator.
 *
 * @see Jyxo_Spl_Object
 * @copyright Copyright (c) 2005-2011 Jyxo, s.r.o.
 * @license https://github.com/jyxo/php/blob/master/license.txt
 * @author Ondřej Nešpor
 */
class Jyxo_Spl_FilterIteratorTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Tests the whole class.
	 */
	public function testIterator()
	{
		$data = range(0, 10);
		$callback = function($value) {
			return 0 == $value % 2;
		};

		$iterator = new Jyxo_Spl_FilterIterator(new ArrayIterator($data), $callback);

		$expected = array(
			0 => 0,
			2 => 2,
			4 => 4,
			6 => 6,
			8 => 8,
			10 => 10
		);

		$results = array();
		foreach ($iterator as $key => $value) {
			$results[$key] = $value;
		}

		$this->assertSame($expected, $results);
		$this->assertSame($expected, $iterator->toArray());
	}

	/**
	 * Tests an invalid callback exception.
	 */
	public function testInvalidCallback()
	{
		$this->setExpectedException('InvalidArgumentException');

		$callback = 'FunctionThatDoesNotExistForSure';
		$data = array();
		$iterator = new Jyxo_Spl_FilterIterator(new ArrayIterator($data), $callback);

		$this->fail('Expected InvalidArgumentException exception');

	}

}
