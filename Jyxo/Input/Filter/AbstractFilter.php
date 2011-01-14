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
 * Parent class of all filters.
 * Allows multidimensional arrays filtering.
 *
 * @category Jyxo
 * @package Jyxo_Input
 * @subpackage Filter
 * @copyright Copyright (c) 2005-2011 Jyxo, s.r.o.
 * @license https://github.com/jyxo/php/blob/master/license.txt
 * @author Jaroslav Hanslík
 */
abstract class Jyxo_Input_Filter_AbstractFilter implements Jyxo_Input_FilterInterface
{
	/**
	 * Creates a filter instance.
	 */
	public function __construct()
	{}

	/**
	 * Filters a value.
	 *
	 * @param mixed $value Input value
	 * @return mixed
	 */
	public static function filtrate($value)
	{
		$filter = new static();
		return $filter->filter($value);
	}

	/**
	 * Actually filters a value.
	 *
	 * @param mixed $in Input value
	 * @return mixed
	 */
	abstract protected function filterValue($in);

	/**
	 * Filters a value.
	 *
	 * @param mixed $in Object to be filtered
	 * @return Jyxo_Input_FilterInterface This object instance
	 */
	public function filter($in)
	{
		if (is_array($in)) {
			return array_map(array($this, 'filter'), $in);
		}
		return $this->filterValue($in);
	}
}
