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
 * Simple object cache so we don't have to create them or write caching over again.
 *
 * <?php
 * // Example code
 * <code>
 * 	$key = 'User_Friends/' . $user->username();
 * 	return Jyxo_Spl_ObjectCache::get($key) ?: Jyxo_Spl_ObjectCache::set($key, new User_Friends($this->context, $user));
 * </code>
 *
 * @category Jyxo
 * @package Jyxo_Spl
 * @copyright Copyright (c) 2005-2011 Jyxo, s.r.o.
 * @license https://github.com/jyxo/php/blob/master/license.txt
 * @author Jakub Tománek
 */
class Jyxo_Spl_ObjectCache implements IteratorAggregate
{
	/**
	 * Object storage.
	 *
	 * @var array
	 */
	private $storage = array();

	/**
	 * Returns default storage for static access.
	 *
	 * @return Jyxo_Spl_ObjectCache
	 */
	public static function getInstance()
	{
		static $instance = null;
		if (null === $instance) {
			$instance = new self();
		}
		return $instance;
	}

	/**
	 * Returns an object from the default storage.
	 *
	 * @param string $key Object key
	 * @return object
	 */
	public static function get($key)
	{
		return self::getInstance()->$key;
	}

	/**
	 * Clear the whole storage.
	 *
	 * @return Jyxo_Spl_ObjectCache
	 */
	public function clear()
	{
		$this->storage = array();
		return $this;
	}

	/**
	 * Saves an object into the default storage.
	 *
	 * @param string $key Object key
	 * @param object $value Object
	 * @return object saved object
	 */
	public static function set($key, $value)
	{
		self::getInstance()->$key = $value;
		return $value;
	}

	/**
	 * Returns an object from an own storage.
	 *
	 * @param string $key Object key
	 * @return object
	 */
	public function __get($key)
	{
		return isset($this->storage[$key]) ? $this->storage[$key] : null;
	}

	/**
	 * Saves an object into an own storage.
	 *
	 * @param string $key Object key
	 * @param object $value Object
	 */
	public function __set($key, $value)
	{
		$this->storage[$key] = $value;
	}

	/**
	 * Returns if there's an object with key $key in the storage.
	 *
	 * @param string $key Object key
	 * @return boolean
	 */
	public function __isset($key)
	{
		return isset($this->storage[$key]);
	}

	/**
	 * Deletes an object with key $key from the storage.
	 *
	 * @param mixed $key Object key
	 */
	public function __unset($key)
	{
		unset ($this->storage[$key]);
	}

	/**
	 * Returns an iterator.
	 *
	 * @return ArrayIterator
	 */
	public function getIterator()
	{
		return new ArrayIterator($this->storage);
	}
}
