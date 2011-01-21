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
 * Test result.
 *
 * @category Jyxo
 * @package Jyxo_Beholder
 * @copyright Copyright (c) 2005-2011 Jyxo, s.r.o.
 * @license https://github.com/jyxo/php/blob/master/license.txt
 * @author Jan Matoušek
 * @author Jaroslav Hanslík
 */
class Jyxo_Beholder_Result
{
	/**
	 * Success.
	 *
	 * @var string
	 */
	const SUCCESS = 'success';

	/**
	 * Failure.
	 *
	 * @var string
	 */
	const FAILURE = 'failure';

	/**
	 * Not-applicable test.
	 *
	 * @var string
	 */
	const NOT_APPLICABLE = 'not-applicable';

	/**
	 * List of statuses.
	 *
	 * @var array
	 */
	private static $statusList = array(
		self::SUCCESS => 'OK',
		self::FAILURE => 'FAILED',
		self::NOT_APPLICABLE => 'NOT APPLICABLE'
	);

	/**
	 * Status.
	 *
	 * @var boolean
	 */
	private $status;

	/**
	 * Description.
	 *
	 * @var string
	 */
	private $description = '';


	/**
	 * Result constructor.
	 *
	 * @param string $status Result status
	 * @param string $description Status description
	 * @throws InvalidArgumentException On an unknown status
	 */
	public function __construct($status, $description = '')
	{
		// Checks status
		$status = (string) $status;
		if (!isset(self::$statusList[$status])) {
			throw new InvalidArgumentException(sprintf('Invalid status %s', $status));
		}
		$this->status = $status;

		// Sets description
		$description = (string) $description;
		if (empty($description)) {
			$description = self::$statusList[$status];
		}
		$this->description = $description;
	}

	/**
	 * Returns if the test was successful.
	 *
	 * @return boolean
	 */
	public function isSuccess()
	{
		return ($this->status !== self::FAILURE);
	}

	/**
	 * Returns the test status.
	 *
	 * @return string
	 */
	public function getStatus()
	{
		return $this->status;
	}

	/**
	 * Returns the status message.
	 *
	 * @return string
	 */
	public function getStatusMessage()
	{
		return self::$statusList[$this->status];
	}

	/**
	 * Returns the description.
	 *
	 * @return string
	 */
	public function getDescription()
	{
		return $this->description;
	}
}
