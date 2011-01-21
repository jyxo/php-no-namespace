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
 * Tests Zend Framework availability and optionally its version.
 *
 * @category Jyxo
 * @package Jyxo_Beholder
 * @copyright Copyright (c) 2005-2011 Jyxo, s.r.o.
 * @license https://github.com/jyxo/php/blob/master/license.txt
 * @author Jaroslav Hanslík
 */
class Jyxo_Beholder_TestCase_PhpZend extends Jyxo_Beholder_TestCase
{
	/**
	 * Required version.
	 *
	 * @var string
	 */
	private $version;

	/**
	 * Comparison operator: <, >, =, <=, >=.
	 *
	 * @var string
	 */
	private $comparison;

	/**
	 * Constructor.
	 *
	 * @param string $description Test description
	 * @param string $version Required version
	 * @param string $comparison Comparison operator
	 */
	public function __construct($description, $version = '', $comparison = '=')
	{
		parent::__construct($description);

		$this->version = (string) $version;
		$this->comparison = (string) $comparison;
	}

	/**
	 * Performs the test.
	 *
	 * @return Jyxo_Beholder_Result
	 */
	public function run()
	{
		// Zend Framework availability
		if (!class_exists('Zend_Version')) {
			return new Jyxo_Beholder_Result(Jyxo_Beholder_Result::FAILURE, 'Zend framework missing');
		}

		$result = Jyxo_Beholder_Result::SUCCESS;

		// Version comparison if requested

		if (!empty($this->version)) {

			$comparison = Zend_Version::compareVersion($this->version);

			switch ($this->comparison) {
				case '<':
					$result = ($comparison > 0) ? Jyxo_Beholder_Result::SUCCESS : Jyxo_Beholder_Result::FAILURE;
					break;

				case '<=':
					$result = ($comparison >= 0) ? Jyxo_Beholder_Result::SUCCESS : Jyxo_Beholder_Result::FAILURE;
					break;

				case '>=':
					$result = ($comparison <= 0) ? Jyxo_Beholder_Result::SUCCESS : Jyxo_Beholder_Result::FAILURE;
					break;

				case '>':
					$result = ($comparison < 0) ? Jyxo_Beholder_Result::SUCCESS : Jyxo_Beholder_Result::FAILURE;
					break;

				default:
					$this->comparison = '=';
					$result = ($comparison === 0) ? Jyxo_Beholder_Result::SUCCESS : Jyxo_Beholder_Result::FAILURE;
					break;
			}

			return new Jyxo_Beholder_Result($result, sprintf('Version %s, expected %s %s', Zend_Version::VERSION, $this->comparison, $this->version));

		}

		// OK
		return new Jyxo_Beholder_Result($result, sprintf('Version %s', Zend_Version::VERSION));
	}
}
