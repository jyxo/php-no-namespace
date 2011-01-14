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
 * Class implementing "fluent" design pattern for Jyxo_Input.
 *
 * Allows chaining multiple validators and checking multiple values in one validation cycle.
 *
 * @category Jyxo
 * @package Jyxo_Input
 * @copyright Copyright (c) 2005-2011 Jyxo, s.r.o.
 * @license https://github.com/jyxo/php/blob/master/license.txt
 * @author Jakub Tománek
 */
class Jyxo_Input_Fluent
{
	/**
	 * Validator class names prefix.
	 *
	 * @var string
	 */
	const VALIDATORS_PREFIX = 'Jyxo_Input_Validator_';

	/**
	 * Filter class names prefix.
	 *
	 * @var string
	 */
	const FILTERS_PREFIX = 'Jyxo_Input_Filter_';

	/**
	 * All chains.
	 *
	 * @var array
	 */
	private $chains = array();

	/**
	 * All values.
	 *
	 * @var array
	 */
	private $values = array();

	/**
	 * Default variable values.
	 *
	 * @var array
	 */
	private $default = array();

	/**
	 * Current variable.
	 *
	 * @var string
	 */
	private $currentName;

	/**
	 * Current chain.
	 *
	 * @var Jyxo_Input_Chain
	 */
	private $chain = null;

	/**
	 * Errors.
	 *
	 * @var array
	 */
	private $errors = array();

	/**
	 * Jyxo_Input objects factory.
	 *
	 * @var Jyxo_Input_Factory
	 */
	private $factory;

	/**
	 * Constructor.
	 */
	public function __construct()
	{
		$this->factory = new Jyxo_Input_Factory();
		$this->all();
	}

	/**
	 * Starts a new value checking.
	 *
	 * @param mixed $var Value to check.
	 * @param string $name Variable name
	 * @return Jyxo_Input_Fluent
	 */
	public function check($var, $name)
	{
		$this->chain = new Jyxo_Input_Chain();
		$this->chains[$name] = $this->chain;
		$this->values[$name] = $var;
		$this->default[$name] = null;
		$this->currentName = $name;
		return $this;
	}

	/**
	 * Validates all variables.
	 *
	 * @return Jyxo_Input_Fluent
	 */
	public function all()
	{
		$this->chain = new Jyxo_Input_Chain();
		$this->chains[uniqid('fluent:')] = $this->chain;
		$this->currentName = null;
		return $this;
	}

	/**
	 * Sets a default value in case the validation fails.
	 *
	 * @param mixed $value Default value
	 * @return Jyxo_Input_Fluent
	 * @throws BadMethodCallException There is no active variable.
	 */
	public function defaultValue($value)
	{
		if (null === $this->currentName) {
			throw new BadMethodCallException('No active variable');
		}

		$this->default[$this->currentName] = $value;

		return $this;
	}

	/**
	 * Adds a validator to the chain.
	 *
	 * @param string $name Validator name
	 * @param string $errorMessage Validator error message
	 * @param mixed $param Additional validator parameter
	 * @return Jyxo_Input_Fluent
	 */
	public function validate($name, $errorMessage = null, $param = null)
	{
		$this->chain->addValidator($this->factory->getValidatorByName($name, $param), $errorMessage);
		return $this;
	}

	/**
	 * Adds a filter to the chain.s
	 *
	 * @param string $name Filter name
	 * @param mixed $param Additional filter parameter
	 * @return Jyxo_Input_Fluent
	 */
	public function filter($name, $param = null)
	{
		$this->chain->addFilter($this->factory->getFilterByName($name, $param));
		return $this;
	}

	/**
	 * Adds a subchain to the current chain that treats the value a an array.
	 * Automatically adds the isArray validator.
	 *
	 * @param boolean $addFilter Add the Trim filter (removes empty elements)
	 * @return Jyxo_Input_Fluent
	 */
	public function walk($addFilter = true)
	{
		$this->validate('isArray');
		if (false != $addFilter) {
			$this->filter('trim');
		}
		$this->chain = $this->chain->addWalk();
		return $this;
	}

	/**
	 * Adds a conditional chain.
	 *
	 * If there are conditions in the current chain, adds the condition as a subchain.
	 *
	 * @param string $name Validator name
	 * @param mixed $param Additional validator parameter
	 * @return Jyxo_Input_Fluent
	 * @throws BadMethodCallException There is no active variable
	 */
	public function condition($name, $param = null)
	{
		$condChain = new Jyxo_Input_Chain_Conditional($this->factory->getValidatorByName($name, $param));
		if (true === $this->chain->isEmpty()) {
			// The actual chain is empty, can be replaced by the condition
			$this->chain = $condChain;
			if (null === $this->currentName) {
				throw new BadMethodCallException('No active variable');
			}
			$this->chains[$this->currentName] = $condChain;
		} else {
			$this->chain = $this->chain->addCondition($condChain);
		}
		return $this;
	}

	/**
	 * Closes a chain.
	 *
	 * @return Jyxo_Input_Fluent
	 */
	public function close()
	{
		$this->chain = $this->chain->close();
		return $this;
	}

	/**
	 * Performs validation and filtering of all variables.
	 *
	 * @param boolean $assocErrors Return error messages in an associative array
	 * @return boolean
	 */
	public function isValid($assocErrors = false)
	{
		$valid = true;
		foreach ($this->chains as $name => $chain) {
			/* @var $chain Jyxo_Input_Chain */
			if (array_key_exists($name, $this->values)) {
				// Variable
				if (!$this->checkChain($chain, $this->values[$name], $this->default[$name], $assocErrors ? $name : null)) {
					$valid = false;
				}
			} elseif (!$chain->isEmpty()) {
				foreach ($this->values as $name => &$value) {
					if (!$this->checkChain($chain, $value, $this->default[$name])) {
						$valid = false;
						// No need to check other variables
						break;
					}
				}
			}
		}

		return $valid;
	}

	/**
	 * Calls isValid(), but throws an exception on error.
	 *
	 * The exception contains only the first validation error message.
	 *
	 * @throws Jyxo_Input_Validator_Exception Validation failed
	 */
	public function validateAll()
	{
		if (!$this->isValid()) {
			throw new Jyxo_Input_Validator_Exception(reset($this->errors));
		}
	}

	/**
	 * Checks a chain.
	 *
	 * @param Jyxo_Input_Chain $chain Validation chain
	 * @param mixed $value Input value
	 * @param mixed $default Default value to be used in case the validation fails
	 * @param string $name Chain name to be used in the error array
	 * @return boolean
	 */
	private function checkChain(Jyxo_Input_Chain $chain, &$value, $default, $name = null)
	{
		$valid = true;
		if ($chain->isValid($value)) {
			$value = $chain->getValue();
		} elseif (null !== $default) {
			$value = $default;
		} else {
			$valid = false;
			// If we have $name set, we want an associative array
			$errors = empty($name) ? $chain->getErrors() : array($name => $chain->getErrors());
			$this->errors = array_merge($this->errors, $errors);
		}
		return $valid;
	}

	/**
	 * Returns all values.
	 *
	 * @return array
	 */
	public function getValues()
	{
		return $this->values;
	}

	/**
	 * Returns a value by name.
	 *
	 * @param string $name Variable name
	 * @return mixed
	 * @throws Jyxo_Input_Exception No variable with the given name
	 */
	public function getValue($name)
	{
		if (!array_key_exists($name, $this->values)) {
			throw new Jyxo_Input_Exception('Value is not present');
		}

		return $this->values[$name];
	}

	/**
	 * Returns errors.
	 *
	 * @return array
	 */
	public function getErrors()
	{
		return $this->errors;
	}

	/**
	 * Checks a POST variable.
	 *
	 * @param string $name Variable name
	 * @param mixed $default Default value
	 * @return Jyxo_Input_Fluent
	 */
	public function post($name, $default = null)
	{
		$this->addToCheck($_POST, $name, $default);
		return $this;
	}

	/**
	 * Checks a GET variable.
	 *
	 * @param string $name Variable name
	 * @param mixed $default Default value
	 * @return Jyxo_Input_Fluent
	 */
	public function query($name, $default = null)
	{
		$this->addToCheck($_GET, $name, $default);
		return $this;
	}

	/**
	 * Checks a POST/GET variable
	 *
	 * @param string $name Variable name
	 * @param mixed $default Default value
	 * @return Jyxo_Input_Fluent
	 */
	public function request($name, $default = null)
	{
		$this->addToCheck($_REQUEST, $name, $default);
		return $this;
	}

	/**
	 * Checks file upload.
	 *
	 * Requires Jyxo_Input_Upload.
	 *
	 * @param string $index File index
	 * @see Jyxo_Input_Upload
	 * @return Jyxo_Input_Fluent
	 */
	public function file($index)
	{
		$validator = new Jyxo_Input_Validator_Upload();
		$file = new Jyxo_Input_Upload($index);
		$this
			->check($file, $index)
				->validate($validator)
				->filter($validator);
		return $this;
	}

	/**
	 * Adds a variable to the chain.
	 *
	 * @param array $global Variable array
	 * @param string $name Variable name
	 * @param mixed $default Default value
	 */
	private function addToCheck(array $global, $name, $default = null)
	{
		$var = isset($global[$name]) ? $global[$name] : $default;
		$this->check($var, $name);
	}

	/**
	 * Magic getter for easier retrieving of values.
	 *
	 * @param string $offset Value name
	 * @return mixed
	 * @throws Jyxo_Input_Exception No variable with the given name
	 */
	public function __get($offset)
	{
		return $this->getValue($offset);
	}
}
