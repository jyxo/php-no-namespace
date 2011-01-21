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
 * Tests XML-RPC server availability.
 *
 * @category Jyxo
 * @package Jyxo_Beholder
 * @copyright Copyright (c) 2005-2011 Jyxo, s.r.o.
 * @license https://github.com/jyxo/php/blob/master/license.txt
 * @author Jaroslav Hanslík
 */
class Jyxo_Beholder_TestCase_XmlRpc extends Jyxo_Beholder_TestCase
{
	/**
	 * XML-RPC server URL.
	 *
	 * @var string
	 */
	private $url;

	/**
	 * Method name.
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
	 * Request options.
	 *
	 * @var array
	 */
	private $options = array();

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
	 * @param string $method Method name
	 * @param array $params Method parameters
	 * @param array $options Request options
	 * @param integer $timeout Timeout
	 */
	public function __construct($description, $url, $method, array $params, array $options = array(), $timeout = 2)
	{
		parent::__construct($description);

		$this->url = (string) $url;
		$this->method = (string) $method;
		$this->params = $params;
		$this->options = $options;
		$this->timeout = (int) $timeout;
	}

	/**
	 * Performs the test.
	 *
	 * @return Jyxo_Beholder_Result
	 */
	public function run()
	{
		// The xmlrpc extension is required
		if (!extension_loaded('xmlrpc')) {
			return new Jyxo_Beholder_Result(Jyxo_Beholder_Result::NOT_APPLICABLE, 'Extension xmlrpc missing');
		}

		// The curl extension is required
		if (!extension_loaded('curl')) {
			return new Jyxo_Beholder_Result(Jyxo_Beholder_Result::NOT_APPLICABLE, 'Extension curl missing');
		}

		// The Jyxo_Rpc_Xml_Client class is required
		if (!class_exists('Jyxo_Rpc_Xml_Client')) {
			return new Jyxo_Beholder_Result(Jyxo_Beholder_Result::NOT_APPLICABLE, 'Class Jyxo_Rpc_Xml_Client missing');
		}

		// Create the RPC client
		$rpc = new Jyxo_Rpc_Xml_Client();
		foreach ($this->options as $name => $value) {
			$rpc->setOption($name, $value);
		}
		$rpc->setUrl($this->url)
			->setTimeout($this->timeout);

		// Send the request
		try {
			$rpc->send($this->method, $this->params);
		} catch (Exception $e) {
			return new Jyxo_Beholder_Result(Jyxo_Beholder_Result::FAILURE, $this->url);
		}

		// OK
		return new Jyxo_Beholder_Result(Jyxo_Beholder_Result::SUCCESS, $this->url);
	}
}
