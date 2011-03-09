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

require_once __DIR__ . '/../bootstrap.php';

/**
 * Jyxo_Timer test.
 *
 * @copyright Copyright (c) 2005-2011 Jyxo, s.r.o.
 * @license https://github.com/jyxo/php/blob/master/license.txt
 * @author Jakub Tománek
 */
class Jyxo_TimerTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Tests duration measuring.
	 */
	public function testDuration()
	{
		$mt = microtime(true);
		$name = Jyxo_Timer::start();
		$delta = Jyxo_Timer::stop($name);
		$outerDelta = microtime(true) - $mt;

		// Measured time is greater than 0
		$this->assertGreaterThan(0, $delta);

		// Measured time is less than the time between the two function calls
		$this->assertLessThan($outerDelta, $delta);

		// Non-existent timer
		$this->assertSame(0, Jyxo_Timer::stop('foo'));

		// Start 4 timers
		$names = array('foo', 'bar', 'tmp', 'ohai');
		$times = array_fill_keys($names, 0);
		foreach ($names as $name) {
			Jyxo_Timer::start($name);
		}
		// End them in reverse order
		foreach (array_reverse($names) as $name) {
			$times[$name] = Jyxo_Timer::stop($name);
		}

		// The measured time is supposed to be in descending order
		foreach ($names as $i => $name) {
			$this->assertGreaterThan(0, $times[$name]);
			if ($i > 0) {
				$this->assertLessThan($times[$names[$i - 1]], $times[$name]);
			}
		}
	}

	/**
	 * Tests the timer function.
	 *
	 * Originally the usleep() function took the $i variable as the number of miliseconds.
	 * However because the real time the script execution is halted is not exactly the
	 * number of miliseconds given (sometimes slightly less, but enough to make the assertion
	 * fail), we multiply the sleep time by 1.1 just to be sure.
	 */
	public function testTimer()
	{
		Jyxo_Timer::timer();
		for ($i = 100; $i < 1000000; $i *= 10) {
			usleep($i * 1.1);
			$delta = Jyxo_Timer::timer();

			$this->assertGreaterThan($i / 1000000, $delta);
		}
	}
}
