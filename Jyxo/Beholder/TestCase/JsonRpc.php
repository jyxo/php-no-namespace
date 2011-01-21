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
 * Tests JSON-RPC server availability.
 *
 * @category Jyxo
 * @package Jyxo_Beholder
 * @copyright Copyright (c) 2005-2011 Jyxo, s.r.o.
 * @license https://github.com/jyxo/php/blob/master/license.txt
 * @author Jan Pěček
 * @author Jaroslav Hanslík
 */
class Jyxo_Beholder_TestCase_JsonRpc extends Jyxo_Beholder_TestCase
{
	/**
	 * JSON-RPC server URL.
	 *
	 * @var string
	 */
	private $url;

	/**
	 * Tested method.
	 *
	 * @var string
	 */
	private $method;

	/**
	 * Method parameters.
	 *
	 * @var string
	 */
	private $params = array();

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
	 * @param string $url Server URL
	 * @param string $method Tested method
	 * @param array $params Method parameters
	 * @param integer $timeout Timeout
	 */
	public function __construct($description, $url, $method, array $params, $timeout = 2)
	{
		parent::__construct($description);

		$this->url = (string) $url;
		$this->method = (string) $method;
		$this->params = $params;
		$this->timeout = (int) $timeout;
	}

	/**
	 * Performs the test.
	 *
	 * @return Jyxo_Beholder_Result
	 */
	public function run()
	{
		// The json extension is required
		if (!extension_loaded('json')) {
			return new Jyxo_Beholder_Result(Jyxo_Beholder_Result::NOT_APPLICABLE, 'Extension json missing');
		}

		// The curl extension is required
		if (!extension_loaded('curl')) {
			return new Jyxo_Beholder_Result(Jyxo_Beholder_Result::NOT_APPLICABLE, 'Extension curl missing');
		}

		// The Jyxo_Rpc_Json_Client class is required
		if (!class_exists('Jyxo_Rpc_Json_Client')) {
			return new Jyxo_Beholder_Result(Jyxo_Beholder_Result::NOT_APPLICABLE, 'Class Jyxo_Rpc_Json_Client missing');
		}

		// Creates a client
		$rpc = new Jyxo_Rpc_Json_Client();
		$rpc->setUrl($this->url)
			->setTimeout($this->timeout);

		// Sends the request
		try {
			$rpc->send($this->method, $this->params);
		} catch (Exception $e) {
			return new Jyxo_Beholder_Result(Jyxo_Beholder_Result::FAILURE, $this->url);
		}

		// OK
		return new Jyxo_Beholder_Result(Jyxo_Beholder_Result::SUCCESS, $this->url);
	}
}
