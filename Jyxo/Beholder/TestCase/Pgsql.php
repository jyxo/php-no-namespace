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
 * Tests PostgreSQL availability.
 *
 * @category Jyxo
 * @package Jyxo_Beholder
 * @copyright Copyright (c) 2005-2011 Jyxo, s.r.o.
 * @license https://github.com/jyxo/php/blob/master/license.txt
 * @author Jaroslav Hanslík
 */
class Jyxo_Beholder_TestCase_Pgsql extends Jyxo_Beholder_TestCase
{
	/**
	 * SQL query.
	 *
	 * @var string
	 */
	private $query;

	/**
	 * Database name.
	 *
	 * @var string
	 */
	private $database;

	/**
	 * Hostname.
	 *
	 * @var string
	 */
	private $host;

	/**
	 * Username.
	 *
	 * @var string
	 */
	private $user;

	/**
	 * Password.
	 *
	 * @var string
	 */
	private $password;

	/**
	 * Port.
	 *
	 * @var integer
	 */
	private $port;

	/**
	 * Timeout.
	 *
	 * @var integer
	 */
	private $timeout;

	/**
	 * Constructor.
	 *
	 * @param string $description Test description
	 * @param string $query Tested query
	 * @param string $database Database name
	 * @param string $host Hostname
	 * @param string $user Username
	 * @param string $password Password
	 * @param integer $port Port
	 * @param integer $timeout Timeout
	 */
	public function __construct($description, $query, $database, $host = 'localhost', $user = '', $password = '', $port = 5432, $timeout = 2)
	{
		parent::__construct($description);

		$this->query = (string) $query;
		$this->database = (string) $database;
		$this->host = (string) $host;
		$this->user = (string) $user;
		$this->password = (string) $password;
		$this->port = (int) $port;
		$this->timeout = (int) $timeout;
	}

	/**
	 * Performs the test.
	 *
	 * @return Jyxo_Beholder_Result
	 */
	public function run()
	{
		// The pgsql extension is required
		if (!extension_loaded('pgsql')) {
			return new Jyxo_Beholder_Result(Jyxo_Beholder_Result::NOT_APPLICABLE, 'Extension pgsql missing');
		}

		// Status label
		$description = sprintf('%s@%s:%s/%s', $this->user, $this->host, $this->port, $this->database);

		// Connection
		$db = pg_connect(sprintf(
			'host=%s port=%d dbname=%s user=%s password=%s connect_timeout=%d',
			$this->host, $this->port, $this->database, $this->user, $this->password, $this->timeout
		));
		if (false === $db) {
			return new Jyxo_Beholder_Result(Jyxo_Beholder_Result::FAILURE, sprintf('Connection error %s', $description));
		}

		// Query (the leading space is because of pgpool)
		$result = pg_query($db, ' ' . $this->query);
		if (false === $result) {
			pg_close($db);
			return new Jyxo_Beholder_Result(Jyxo_Beholder_Result::FAILURE, sprintf('Query error %s', $description));
		}

		pg_free_result($result);
		pg_close($db);

		// OK
		return new Jyxo_Beholder_Result(Jyxo_Beholder_Result::SUCCESS, $description);
	}
}
