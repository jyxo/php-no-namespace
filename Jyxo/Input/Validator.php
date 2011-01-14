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
 * Class for easier one-line validation.
 *
 * @category Jyxo
 * @package Jyxo_Input
 * @subpackage Validator
 * @copyright Copyright (c) 2005-2011 Jyxo, s.r.o.
 * @license https://github.com/jyxo/php/blob/master/license.txt
 * @author Jakub Tománek
 */
class Jyxo_Input_Validator
{
	/**
	 * Static validation.
	 *
	 * @param string $method Validator name
	 * @param array $params Parameters; the first value gets validated, the rest will be used as constructor parameters
	 * @return boolean
	 */
	public static function __callStatic($method, array $params)
	{
		$factory = Jyxo_Spl_ObjectCache::get('Jyxo_Input_Factory') ?: Jyxo_Spl_ObjectCache::set('Jyxo_Input_Factory', new Jyxo_Input_Factory());
		$value = array_shift($params);
		$key = 'Jyxo_Input_Validator_' . ucfirst($method) . ($params ? '/' . serialize($params) : '');
		$validator = Jyxo_Spl_ObjectCache::get($key) ?: Jyxo_Spl_ObjectCache::set($key, $factory->getValidatorByName($method, $params));
		return $validator->isValid($value);
	}
}
