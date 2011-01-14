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
 * Object which can be converted to an array.
 *
 * @category Jyxo
 * @package Jyxo_Spl
 * @copyright Copyright (c) 2005-2011 Jyxo, s.r.o.
 * @license https://github.com/jyxo/php/blob/master/license.txt
 * @author Jakub Tománek
 */
interface Jyxo_Spl_ArrayCopy
{
	/**
	 * Converts an object to an array.
	 *
	 * @return array
	 */
	public function toArray();
}
