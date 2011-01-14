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
 * Test for the Jyxo_Time_ComposerException class.
 *
 * @see Jyxo_Time_ComposerException
 * @copyright Copyright (c) 2005-2011 Jyxo, s.r.o.
 * @license https://github.com/jyxo/php/blob/master/license.txt
 * @author Jaroslav Hanslík
 */
class Jyxo_Time_ComposerExceptionTest extends PHPUnit_Framework_TestCase
{
	/**
	 * The whole test.
	 */
	public function test()
	{
		// All possible codes.
		$reflection = new ReflectionClass('Jyxo_Time_ComposerException');
		foreach ($reflection->getConstants() as $code) {
			$exception = new Jyxo_Time_ComposerException('Test', $code);
			$this->assertEquals($code, $exception->getCode());
		}

		// Non-existent code
		$exception = new Jyxo_Time_ComposerException('Test', 'dummy-code');
		$this->assertEquals(Jyxo_Time_ComposerException::UNKNOWN, $exception->getCode());
	}
}
