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
 * Test for the Jyxo_Beholder_TestCase_FileSystem class.
 *
 * @see Jyxo_Beholder_TestCase_FileSystem
 * @copyright Copyright (c) 2005-2011 Jyxo, s.r.o.
 * @license https://github.com/jyxo/php/blob/master/license.txt
 * @author Jaroslav Hanslík
 */
class Jyxo_Beholder_TestCase_FileSystemTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Protocol name.
	 *
	 * @var string
	 */
	private $protocol = 'test';

	/**
	 * Tested directory.
	 *
	 * @var string
	 */
	private $dir = 'test://';

	/**
	 * Prepares the testing environment..
	 */
	protected function setUp()
	{
		require_once DIR_FILES . '/beholder/TestFileSystemStream.php';
		TestFileSystemStream::register($this->protocol);
	}

	/**
	 * Cleans up the testing environment.
	 */
	protected function tearDown()
	{
		TestFileSystemStream::unregister($this->protocol);
	}

	/**
	 * Tests write failure.
	 */
	public function testWriteFailure()
	{
		TestFileSystemStream::setError(TestFileSystemStream::ERROR_WRITE);

		$test = new Jyxo_Beholder_TestCase_FileSystem('FileSystem', $this->dir);
		// @ on purpose
		$result = @$test->run();
		$this->assertEquals(Jyxo_Beholder_Result::FAILURE, $result->getStatus());
		$this->assertEquals(sprintf('Write error %s', $this->dir), $result->getDescription());
	}

	/**
	 * Tests read failure.
	 */
	public function testReadFailure()
	{
		TestFileSystemStream::setError(TestFileSystemStream::ERROR_READ);

		$test = new Jyxo_Beholder_TestCase_FileSystem('FileSystem', $this->dir);
		$result = $test->run();
		$this->assertEquals(Jyxo_Beholder_Result::FAILURE, $result->getStatus());
		$this->assertEquals(sprintf('Read error %s', $this->dir), $result->getDescription());
	}

	/**
	 * Tests delete failure.
	 */
	public function testDeleteFailure()
	{
		TestFileSystemStream::setError(TestFileSystemStream::ERROR_DELETE);

		$test = new Jyxo_Beholder_TestCase_FileSystem('FileSystem', $this->dir);
		$result = $test->run();
		$this->assertEquals(Jyxo_Beholder_Result::FAILURE, $result->getStatus());
		$this->assertEquals(sprintf('Delete error %s', $this->dir), $result->getDescription());
	}

	/**
	 * Tests all functions.
	 */
	public function testAllOk()
	{
		TestFileSystemStream::setError(TestFileSystemStream::ERROR_NONE);

		$test = new Jyxo_Beholder_TestCase_FileSystem('FileSystem', $this->dir);
		$result = $test->run();
		$this->assertEquals(Jyxo_Beholder_Result::SUCCESS, $result->getStatus());
		$this->assertEquals($this->dir, $result->getDescription());
	}
}
