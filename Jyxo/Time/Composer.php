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
 * Time composer used to compose a date/time part by part.
 *
 * @category Jyxo
 * @package Jyxo_Time
 * @copyright Copyright (c) 2005-2011 Jyxo, s.r.o.
 * @license https://github.com/jyxo/php/blob/master/license.txt
 * @author Martin Šamšula
 */
class Jyxo_Time_Composer
{
	/**
	 * Maximal year.
	 *
	 * @var integer
	 */
	const YEAR_MAX = 2037;

	/**
	 * Minimal year.
	 *
	 * @var integer
	 */
	const YEAR_MIN = 1902;

	/**
	 * Day.
	 *
	 * @var integer
	 */
	private $day = 0;

	/**
	 * Month.
	 *
	 * @var integer
	 */
	private $month = 0;

	/**
	 * Year.
	 *
	 * @var integer
	 */
	private $year = 0;

	/**
	 * Second.
	 *
	 * @var integer
	 */
	private $second = 0;

	/**
	 * Minute.
	 *
	 * @var integer
	 */
	private $minute = 0;

	/**
	 * Hour.
	 *
	 * @var integer
	 */
	private $hour = 0;

	/**
	 * Returns the composed date/time.
	 *
	 * @return Jyxo_Time_Time
	 * @throws Jyxo_Time_ComposerException If the date is incomplete or invalid
	 */
	public function getTime()
	{
		if ($this->month === 0 || $this->year === 0 || $this->day === 0) {
			throw new Jyxo_Time_ComposerException('Date not complete.', Jyxo_Time_ComposerException::NOT_COMPLETE);
		}

		// Checkdate checks if the provided day is valid. Month and year are validated in their getters.
		// The year is between 1 and 32767 inclusive.
		if (!checkdate($this->month, $this->day, $this->year)) {
			throw new Jyxo_Time_ComposerException('Day out of range.', Jyxo_Time_ComposerException::INVALID);
		}

		$time = mktime($this->hour, $this->minute, $this->second, $this->month, $this->day, $this->year);
		return new Jyxo_Time_Time($time);
	}

	/**
	 * Sets the day.
	 *
	 * @param integer $day Day of the month
	 * @return Jyxo_Time_Composer
	 * @throws Jyxo_Time_ComposerException If the provided day is invalid
	 */
	public function setDay($day)
	{
		$day = (integer) $day;

		if ($day < 1 || $day > 31) {
			throw new Jyxo_Time_ComposerException('Day out of range.', Jyxo_Time_ComposerException::DAY);
		}

		$this->day = $day;

		return $this;
	}

	/**
	 * Sets the month.
	 *
	 * @param integer $month Month
	 * @return Jyxo_Time_Composer
	 * @throws Jyxo_Time_ComposerException If the month is invalid.
	 */
	public function setMonth($month)
	{
		$month = (integer) $month;

		if ($month < 1 || $month > 12) {
			throw new Jyxo_Time_ComposerException('Month out of range.', Jyxo_Time_ComposerException::MONTH);
		}

		$this->month = $month;

		return $this;
	}

	/**
	 * Sets the year.
	 *
	 * @param integer $year Year
	 * @return Jyxo_Time_Composer
	 * @throws Jyxo_Time_ComposerException If the year is invalid.
	 */
	public function setYear($year)
	{
		$year = (integer) $year;

		if ($year > self::YEAR_MAX || $year < self::YEAR_MIN) {
			throw new Jyxo_Time_ComposerException('Year out of range.', Jyxo_Time_ComposerException::YEAR);
		}

		$this->year = $year;

		return $this;
	}

	/**
	 * Sets seconds.
	 *
	 * @param integer $second Seconds
	 * @return Jyxo_Time_Composer
	 * @throws Jyxo_Time_ComposerException If seconds are invalid.
	 */
	public function setSecond($second)
	{
		$second = (integer) $second;

		if ($second < 0 || $second > 60) {
			throw new Jyxo_Time_ComposerException('Second out of range.', Jyxo_Time_ComposerException::SECOND);
		}

		$this->second = $second;

		return $this;
	}

	/**
	 * Sets minutes.
	 *
	 * @param integer $minute Minutes
	 * @return Jyxo_Time_Composer
	 * @throws Jyxo_Time_ComposerException If minutes are invalid.
	 */
	public function setMinute($minute)
	{
		$minute = (integer) $minute;

		if ($minute < 0 || $minute > 60) {
			throw new Jyxo_Time_ComposerException('Minute out of range.', Jyxo_Time_ComposerException::MINUTE);
		}

		$this->minute = $minute;

		return $this;
	}

	/**
	 * Sets hours.
	 *
	 * @param integer $hour Hours
	 * @return Jyxo_Time_Composer
	 * @throws Jyxo_Time_ComposerException If hours are invalid.
	 */
	public function setHour($hour)
	{
		$hour = (integer) $hour;

		if ($hour < 0 || $hour > 24) {
			throw new Jyxo_Time_ComposerException('Hour out of range.', Jyxo_Time_ComposerException::HOUR);
		}

		$this->hour = $hour;

		return $this;
	}
}
