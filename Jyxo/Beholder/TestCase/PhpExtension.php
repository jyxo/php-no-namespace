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
 * Tests PHP extensions presence.
 *
 * @category Jyxo
 * @package Jyxo_Beholder
 * @copyright Copyright (c) 2005-2011 Jyxo, s.r.o.
 * @license https://github.com/jyxo/php/blob/master/license.txt
 * @author Jaroslav Hanslík
 */
class Jyxo_Beholder_TestCase_PhpExtension extends Jyxo_Beholder_TestCase
{
	/**
	 * List of extensions.
	 *
	 * @var array
	 */
	private $extensionList = array();

	/**
	 * Constructor.
	 *
	 * @param string $description Test description
	 * @param array $extensionList List of extensions
	 */
	public function __construct($description, array $extensionList)
	{
		parent::__construct($description);

		$this->extensionList = $extensionList;
	}

	/**
	 * Performs the test.
	 *
	 * @return Jyxo_Beholder_Result
	 */
	public function run()
	{
		// Check
		$missing = array();
		foreach ($this->extensionList as $extension) {
			if (!extension_loaded($extension)) {
				$missing[] = $extension;
			}
		}

		// Some extensions are missing
		if (!empty($missing)) {
			return new Jyxo_Beholder_Result(Jyxo_Beholder_Result::FAILURE, sprintf('Missing %s', implode(', ', $missing)));
		}

		// OK
		return new Jyxo_Beholder_Result(Jyxo_Beholder_Result::SUCCESS);
	}
}
