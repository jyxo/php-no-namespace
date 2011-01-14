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
 * Jyxo_Mail_Encoding class test.
 *
 * @see Jyxo_Mail_Encoding
 * @copyright Copyright (c) 2005-2011 Jyxo, s.r.o.
 * @license https://github.com/jyxo/php/blob/master/license.txt
 * @author Jaroslav Hanslík
 */
class Jyxo_Mail_EncodingTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Files path.
	 *
	 * @var string
	 */
	private $filePath;

	/**
	 * List of supported encodings.
	 *
	 * @var array
	 */
	private $encodings = array();

	/**
	 * Prepares the testing environment.
	 */
	protected function setUp()
	{
		$this->filePath = DIR_FILES . '/mail';

		$reflection = new ReflectionClass('Jyxo_Mail_Encoding');
		$this->encodings = $reflection->getConstants();
	}

	/**
	 * Tests the constructor.
	 *
	 * @see Jyxo_Mail_Encoding::__construct()
	 */
	public function testConstruct()
	{
		$this->setExpectedException('LogicException');
		$encoding = new Jyxo_Mail_Encoding();
	}

	/**
	 * Tests the isCompatible() method.
	 *
	 * @see Jyxo_Mail_Encoding::isCompatible()
	 */
	public function testIsCompatible()
	{
		// All defined encodings are compatible
		foreach ($this->encodings as $encoding) {
			$this->assertTrue(Jyxo_Mail_Encoding::isCompatible($encoding));
		}

		// Incompatible encodings returns false
		$this->assertFalse(Jyxo_Mail_Encoding::isCompatible('dummy-encoding'));
	}

	/**
	 * Tests the encode() method.
	 *
	 * @see Jyxo_Mail_Encoding::encode()
	 */
	public function testEncode()
	{
		$data = file_get_contents($this->filePath . '/email.html');
		foreach ($this->encodings as $encoding) {
			$encoded = Jyxo_Mail_Encoding::encode($data, $encoding, 75, "\n");
			$this->assertStringEqualsFile($this->filePath . '/encoding-' . $encoding . '.txt', $encoded);
		}

		try {
			Jyxo_Mail_Encoding::encode('data', 'dummy-encoding', 75, "\n");
			$this->fail('Expected exception InvalidArgumentException.');
		} catch (PHPUnit_Framework_AssertionFailedError $e) {
			throw $e;
		} catch (Exception $e) {
			// Correctly thrown exception
			$this->assertInstanceOf('InvalidArgumentException', $e);
		}
	}
}
