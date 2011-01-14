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
 * Interface defining basic filter methods.
 *
 * @category Jyxo
 * @package Jyxo_Input
 * @subpackage Filter
 * @copyright Copyright (c) 2005-2011 Jyxo, s.r.o.
 * @license https://github.com/jyxo/php/blob/master/license.txt
 * @author Jan Pěček
 */
interface Jyxo_Input_FilterInterface
{

	/**
	 * Filters a value.
	 *
	 * Value is passed by reference and therefore it gets altered.
	 *
	 * @param mixed $in Filtered value
	 * @return Jyxo_Input_FilterInterface This object instance
	 */
	public function filter($in);
}
