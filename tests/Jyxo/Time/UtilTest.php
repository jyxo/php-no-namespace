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
 * Tests for the Jyxo_Time_Util class.
 *
 * @see Jyxo_Time_Composer
 * @copyright Copyright (c) 2005-2011 Jyxo, s.r.o.
 * @license https://github.com/jyxo/php/blob/master/license.txt
 * @author Ondřej Nešpor
 */
class Jyxo_Time_UtilTest extends PHPUnit_Framework_TestCase
{

	/**
	 * Tests the isWorkingDay method.
	 */
	public function testIsWorkingDay()
	{
		$free = array(
			'2010-01-01',
			'2010-11-17',
			'2010-12-24',
			'2011-01-16',
			'2011-04-25',
			'2020-04-13'
		);

		$working = array(
			'2010-12-31',
			'2011-07-20',
			'2010-11-11',
			'2020-04-14'
		);

		foreach ($free as $day) {
			$this->assertFalse(Jyxo_Time_Util::isWorkDay(new Jyxo_Time_Time($day)));
		}

		foreach ($working as $day) {
			$this->assertTrue(Jyxo_Time_Util::isWorkDay(new Jyxo_Time_Time($day)));
		}
	}

	/**
	 * Tests the nextMonth() method.
	 */
	public function testNextMonth()
	{
		$dates = array(
			'2010-01-01' => '2010-02-01',
			'2010-01-31' => '2010-02-28',
			'2012-01-31' => '2012-02-29',
			'2010-12-31' => '2011-01-31',
			'2010-08-31' => '2010-09-30'
		);

		foreach ($dates as $current => $expected) {
			$time = new Jyxo_Time_Time($current);
			$next = Jyxo_Time_Util::nextMonth($time);

			$this->assertEquals($expected . ' 00:00:00', $next->format('Y-m-d H:i:s'));
		}

	}

}
