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
 * Test for class Jyxo_Spl_ArrayUtil.
 *
 * @see Jyxo_Spl_ArrayUtil
 * @copyright Copyright (c) 2005-2011 Jyxo, s.r.o.
 * @license https://github.com/jyxo/php/blob/master/license.txt
 * @author Jakub Tománek
 * @author Ondřej Nešpor
 */
class Jyxo_Spl_ArrayUtilTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Tests a simple integer range.
	 */
	public function testRangeInt()
	{
		$range = Jyxo_Spl_ArrayUtil::range(1, 6, function($current) {
			return $current + 1;
		});

		$this->assertEquals(range(1, 6), $range);
	}

	/**
	 * Tests use of custom closures.
	 */
	public function testRangeCompare()
	{
		$called = 0;
		$range = Jyxo_Spl_ArrayUtil::range(1, 6, function($current) {
			return $current + 1;
		}, function ($a, $b) use (&$called) {
			++$called;
			return $a < $b;
		});

		$this->assertEquals(range(1, 6), $range);
		$this->assertEquals(count($range), $called);
	}

	/**
	 * Tests data range generation.
	 */
	public function testRangeDate()
	{
		$range = Jyxo_Spl_ArrayUtil::range('2010-03-01', '2009-11-01', function($current) {
			return date('Y-m-d', strtotime('first day of last month', strtotime($current)));
		});

		$expect = array(
			'2010-03-01',
			'2010-02-01',
			'2010-01-01',
			'2009-12-01',
			'2009-11-01'
		);

		$this->assertEquals($expect, $range);
	}

	/**
	 * Tests the keymap() method.
	 */
	public function testKeymap()
	{
		$source = array();
		foreach (range(ord('a'), ord('z')) as $value) {
			$source[] = chr($value);
		}
		$traversable = new ArrayIterator($source);

		$closure = function($value) {
			return chr(ord('z') + ord('a') - ord($value));
		};

		$mapped = Jyxo_Spl_ArrayUtil::keymap($traversable, $closure);
		$this->assertSame(array_combine(array_reverse($source), $source), $mapped);

		$mapped = Jyxo_Spl_ArrayUtil::keymap($traversable, $closure, $closure);
		$this->assertSame(array_reverse(array_combine($source, $source)), $mapped);
	}
}
