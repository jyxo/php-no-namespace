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
 * Tests the current PHP version.
 *
 * @category Jyxo
 * @package Jyxo_Beholder
 * @copyright Copyright (c) 2005-2011 Jyxo, s.r.o.
 * @license https://github.com/jyxo/php/blob/master/license.txt
 * @author Jaroslav Hanslík
 */
class Jyxo_Beholder_TestCase_PhpVersion extends Jyxo_Beholder_TestCase
{
	/**
	 * Required version.
	 *
	 * @var string
	 */
	private $version;

	/**
	 * Optional extension test.
	 *
	 * @var string
	 */
	private $extension;

	/**
	 * Comparison type.
	 *
	 * @var string
	 */
	private $comparison;

	/**
	 * Constructor.
	 *
	 * @param string $description Test description
	 * @param string $version Required version
	 * @param string $extension Optional extension name
	 * @param string $comparison Comparison operator for version_compare. >= means "the actual version must be greater or equal to the expected"
	 */
	public function __construct($description, $version, $extension = '', $comparison = '=')
	{
		parent::__construct($description);

		$this->version = (string) $version;
		$this->extension = (string) $extension;
		$this->comparison = (string) $comparison;
	}

	/**
	 * Performs the test.
	 *
	 * @return Jyxo_Beholder_Result
	 */
	public function run()
	{
		// If we test extensions they have to be installed
		if ((!empty($this->extension)) && (!extension_loaded($this->extension))) {
			return new Jyxo_Beholder_Result(Jyxo_Beholder_Result::NOT_APPLICABLE, sprintf('Extension %s missing', $this->extension));
		}

		// Current version
		$actual = !empty($this->extension) ? phpversion($this->extension) : phpversion();

		// Version comparison
		if (true !== version_compare($actual, $this->version, $this->comparison)) {
			return new Jyxo_Beholder_Result(Jyxo_Beholder_Result::FAILURE, sprintf('Version %s, expected %s %s', $actual, $this->comparison, $this->version));
		}

		// OK
		return new Jyxo_Beholder_Result(Jyxo_Beholder_Result::SUCCESS, sprintf('Version %s', $actual));
	}
}
