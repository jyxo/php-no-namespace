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

/**
 * Filesystem access test.
 *
 * @category Jyxo
 * @package Jyxo_Beholder
 * @copyright Copyright (c) 2005-2011 Jyxo, s.r.o.
 * @license https://github.com/jyxo/php/blob/master/license.txt
 * @author Jaroslav Hanslík
 */
class Jyxo_Beholder_TestCase_FileSystem extends Jyxo_Beholder_TestCase
{
	/**
	 * Tested directory.
	 *
	 * @var string
	 */
	private $dir;

	/**
	 * Constructor.
	 *
	 * @param string $description Test description
	 * @param string $dir Tested directory
	 */
	public function __construct($description, $dir)
	{
		parent::__construct($description);

		$this->dir = (string) $dir;
	}

	/**
	 * Performs the test.
	 *
	 * @return Jyxo_Beholder_Result
	 */
	public function run()
	{
		$random = md5(uniqid(time(), true));
		$path = $this->dir . '/beholder-' . $random . '.txt';
		$content = $random;

		// Writing
		if (!file_put_contents($path, $content)) {
			return new Jyxo_Beholder_Result(Jyxo_Beholder_Result::FAILURE, sprintf('Write error %s', $this->dir));
		}

		// Reading
		$readContent = file_get_contents($path);
		if (strlen($readContent) !== strlen($content)) {
			return new Jyxo_Beholder_Result(Jyxo_Beholder_Result::FAILURE, sprintf('Read error %s', $this->dir));
		}

		// Deleting
		if (!@unlink($path)) {
			return new Jyxo_Beholder_Result(Jyxo_Beholder_Result::FAILURE, sprintf('Delete error %s', $this->dir));
		}

		// OK
		return new Jyxo_Beholder_Result(Jyxo_Beholder_Result::SUCCESS, $this->dir);
	}
}
