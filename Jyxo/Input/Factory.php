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
 * Jyxo_Input objects factory.
 *
 * @category Jyxo
 * @package Jyxo_Input
 * @copyright Copyright (c) 2005-2011 Jyxo, s.r.o.
 * @license https://github.com/jyxo/php/blob/master/license.txt
 * @author Jakub Tománek
 */
class Jyxo_Input_Factory
{
	/**
	 * Filter class names prefix.
	 *
	 * @var string
	 */
	private static $filterPrefix = array(
		'Jyxo_Input_Filter_'
	);

	/**
	 * Validator class names prefix.
	 *
	 * @var string
	 */
	private static $validatorPrefix = array(
		'Jyxo_Input_Validator_'
	);

	/**
	 * Returns a particular validator by its name.
	 *
	 * @param string $name Validator name
	 * @param mixed|array $param Validator constructor parameters. In case of a single parameter it can be its value, an array of values otherwise. NULL in case of no parameter.
	 * @return Jyxo_Input_ValidatorInterface
	 * @throws Jyxo_Input_Exception No validator of the given name could be found
	 */
	public function getValidatorByName($name, $param = null)
	{
		if ($name instanceof Jyxo_Input_ValidatorInterface) {
			return $name;
		}

		$params = (array) $param;

		$className = $this->findClass($name, self::$validatorPrefix);
		if (!$className) {
			throw new Jyxo_Input_Exception(sprintf('Could not found "%s" validator', $name));
		}

		return $this->getClass($className, $params);
	}

	/**
	 * Returns a particular filter by its name.
	 *
	 * @param string $name Filter name
	 * @param mixed|array $param Filter constructor parameters. In case of a single parameter it can be its value, an array of values otherwise. NULL in case of no parameter.
	 * @return Jyxo_Input_FilterInterface
	 * @throws Jyxo_Input_Exception No filter of the given name could be found
	 */
	public function getFilterByName($name, $param = null)
	{
		if ($name instanceof Jyxo_Input_FilterInterface) {
			return $name;
		}

		$params = (array) $param;

		$className = $this->findClass($name, self::$filterPrefix);
		if (!$className) {
			throw new Jyxo_Input_Exception(sprintf('Could not found "%s" filter', $name));
		}

		return $this->getClass($className, $params);
	}

	/**
	 * Finds a class by its name and possible prefixes.
	 *
	 * Returns the first found or NULL if no corresponding class was found.
	 *
	 * @param string $name Class name
	 * @param array $prefixes Class prefixes
	 * @return string
	 */
	private function findClass($name, array $prefixes)
	{
		$className = null;
		$name = ucfirst($name);
		foreach ($prefixes as $prefix) {
			$tempName = $prefix . $name;
			if (class_exists($tempName)) {
				$className = $tempName;
				break;
			}
		}
		return $className;
	}

	/**
	 * Creates a class instance with an arbitrary number of parameters.
	 *
	 * @param string $className Class name
	 * @param array $params Parameters array
	 * @return object
	 * @throws ReflectionException An error occurred; the class was probably not found
	 */
	private function getClass($className, array $params)
	{
		$instance = null;
		switch (count($params)) {
			case 0:
				$instance = new $className();
				break;

			case 1:
				$instance = new $className(reset($params));
				break;

			default:
				$reflection = new ReflectionClass($className);
				$instance = $reflection->newInstanceArgs($params);
				break;
		}

		return $instance;
	}

	/**
	 * Registers a new validator prefix.
	 *
	 * The underscore at the end is required; e.g. for class "Api_IsInt" the prefix would be "Api" and validator name "IsInt".
	 *
	 * @param string $prefix Validator class prefix
	 */
	public static function addValidatorPrefix($prefix)
	{
		array_unshift(self::$validatorPrefix, $prefix);
	}

	/**
	 * Registers a new filter prefix.
	 *
	 * The underscore at the end is required; e.g. for class "Api_ToInt" the prefix would be "Api" a filter name "ToInt".
	 *
	 * @param string $prefix
	 */
	public static function addFilterPrefix($prefix)
	{
		array_unshift(self::$filterPrefix, $prefix);
	}
}
