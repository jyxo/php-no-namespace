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

require_once __DIR__ . '/../../bootstrap.php';

/**
 * Test for class Jyxo_Spl_ObjectCache.
 *
 * @see Jyxo_Spl_ObjectCache
 * @copyright Copyright (c) 2005-2011 Jyxo, s.r.o.
 * @license https://github.com/jyxo/php/blob/master/license.txt
 * @author Jan Pěček
 */
class Jyxo_Spl_ObjectCacheTest extends PHPUnit_Framework_TestCase
{

	/**
	 * Cached object key.
	 *
	 * @var string
	 */
	const CACHE_KEY = 'myobject';

	/**
	 * Cache instance.
	 *
	 * @var Jyxo_Spl_ObjectCache
	 */
	private $cache = null;


	/**
	 * Prepares testing environment.
	 */
	protected function setUp()
	{
		$this->cache = Jyxo_Spl_ObjectCache::getInstance();
	}

	/**
	 * Cleans up the environment after testing.
	 */
	protected function tearDown()
	{
		$this->cache = null;
	}

	/**
	 * Tests loading an object that is not stored actually.
	 */
	public function testGetNull()
	{
		$this->assertNull($this->cache->{self::CACHE_KEY});
		$this->assertNull(Jyxo_Spl_ObjectCache::get(self::CACHE_KEY));
	}

	/**
	 * Tests loading data.
	 */
	public function testGetData()
	{
		$object = $this->saveObject();

		$this->assertSame($object, $this->cache->get(self::CACHE_KEY));
		$this->assertSame($this->cache->get(self::CACHE_KEY), $this->cache->{self::CACHE_KEY});

		$this->cache->clear();
	}

	/**
	 * Tests saving data.
	 */
	public function testSaveData()
	{
		$object = $this->saveObject();
		$this->assertSame($object, $this->cache->get(self::CACHE_KEY));

		// Saving using one way
		Jyxo_Spl_ObjectCache::set(self::CACHE_KEY, $object);
		$this->assertSame($object, $this->cache->get(self::CACHE_KEY));

		// Saving using the other way
		$this->cache->{self::CACHE_KEY} = $object;
		$this->assertSame($object, $this->cache->get(self::CACHE_KEY));

		$this->cache->clear();
	}

	/**
	 * Tests isset().
	 */
	public function testIsset()
	{
		// Nothing is saved
		$this->assertFalse(isset($this->cache->{self::CACHE_KEY}));

		// Save and check
		$this->saveObject();
		$this->assertTrue(isset($this->cache->{self::CACHE_KEY}));

		$this->cache->clear();
	}

	/**
	 * Tests unset().
	 */
	public function testUnset()
	{
		// Nothing is saved
		$this->assertFalse(isset($this->cache->{self::CACHE_KEY}));

		// Save and check
		$this->saveObject();
		$this->assertTrue(isset($this->cache->{self::CACHE_KEY}));

		// Unset the object and check
		unset($this->cache->{self::CACHE_KEY});
		$this->assertFalse(isset($this->cache->{self::CACHE_KEY}));

		// Save and check again
		$this->saveObject();
		$this->assertTrue(isset($this->cache->{self::CACHE_KEY}));

		$this->cache->clear();
	}

	/**
	 * Tests cache iterator interface.
	 */
	public function testIterator()
	{
		$objects = array(
			'key1' => new stdClass(),
			'key2' => new stdClass(),
			'key3' => new stdClass()
		);

		// Put items into cache
		foreach ($objects as $key => $object) {
			$this->cache->$key = $object;
		}

		// Iterate over items
		foreach ($this->cache as $key => $item) {
			$this->assertArrayHasKey($key, $objects);
			$this->assertSame($objects[$key], $item);
		}
	}

	/**
	 * Saves an object into cache.
	 *
	 * @return stdClass
	 */
	private function saveObject()
	{
		$object = new stdClass();
		$object->question = 'The Answer to the Ultimate Question of Life, the Universe, and Everything.';
		$object->answer = 42;
		$this->cache->{self::CACHE_KEY} = $object;

		return $object;
	}
}
