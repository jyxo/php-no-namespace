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
 * Class for easier one-line filtering.
 *
 * @category Jyxo
 * @package Jyxo_Input
 * @subpackage Filter
 * @copyright Copyright (c) 2005-2011 Jyxo, s.r.o.
 * @license https://github.com/jyxo/php/blob/master/license.txt
 * @author Jakub Tománek
 */
class Jyxo_Input_Filter
{
	/**
	 * Static filtering.
	 *
	 * @param string $method Filter name
	 * @param array $params Parameters; the first value gets filtered, the rest will be used as constructor parameters
	 * @return mixed
	 */
	public static function __callStatic($method, array $params)
	{
		$factory = Jyxo_Spl_ObjectCache::get('Jyxo_Input_Factory') ?: Jyxo_Spl_ObjectCache::set('Jyxo_Input_Factory', new Jyxo_Input_Factory());
		$value = array_shift($params);
		$key = 'Jyxo_Input_Filter_' . ucfirst($method) . ($params ? '/' . serialize($params) : '');
		$filter = Jyxo_Spl_ObjectCache::get($key) ?: Jyxo_Spl_ObjectCache::set($key, $factory->getFilterByName($method, $params));
		/* @var $filter Jyxo_Input_FilterInterface */
		return $filter->filter($value);
	}
}
