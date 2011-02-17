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
 * Abstract class for sending RPC requests.
 *
 * @category Jyxo
 * @package Jyxo_Rpc
 * @copyright Copyright (c) 2005-2011 Jyxo, s.r.o.
 * @license https://github.com/jyxo/php/blob/master/license.txt
 * @author Jaroslav Hanslík
 */
abstract class Jyxo_Rpc_Client
{
	/**
	 * Server address.
	 *
	 * @var string
	 */
	protected $url = '';

	/**
	 * Time limit for communication with RPC server (seconds).
	 *
	 * @var integer
	 */
	protected $timeout = 5;

	/**
	 * Parameters for creating RPC requests.
	 *
	 * @var array
	 */
	protected $options = array();

	/**
	 * Timer name.
	 *
	 * @var string
	 */
	private $timer = '';

	/**
	 * Whether to use request profiler.
	 *
	 * @var boolean
	 */
	private $profiler = false;

	/**
	 * Creates client instance and eventually sets server address.
	 *
	 * @param string $url Server address
	 */
	public function __construct($url = '')
	{
		if (!empty($url)) {
			$this->setUrl($url);
		}
	}

	/**
	 * Sets server address.
	 *
	 * @param string $url Server address
	 * @return Jyxo_Rpc_Client
	 */
	public function setUrl($url)
	{
		$this->url = (string) $url;

		return $this;
	}

	/**
	 * Sets timeout.
	 *
	 * @param integer $timeout Call timeout
	 * @return Jyxo_Rpc_Client
	 */
	public function setTimeout($timeout)
	{
		$this->timeout = (int) $timeout;

		return $this;
	}

	/**
	 * Changes client settings.
	 *
	 * @param string $key Parameter name
	 * @param mixed $value Parameter value
	 * @return Jyxo_Rpc_Client
	 */
	public function setOption($key, $value)
	{
		if (isset($this->options[$key])) {
			$this->options[$key] = $value;
		}
		return $this;
	}

	/**
	 * Returns certain parameter or whole array of parameters if no parameter name is provided.
	 *
	 * @param string $key Parameter name
	 * @return mixed
	 */
	public function getOption($key = '')
	{
		if (isset($this->options[$key])) {
			return $this->options[$key];
		}
		return $this->options;
	}

	/**
	 * Turns request profiler on.
	 *
	 * @return Jyxo_Rpc_Client
	 */
	public function enableProfiler()
	{
		$this->profiler = true;
		return $this;
	}

	/**
	 * Turns request profiler off.
	 *
	 * @return Jyxo_Rpc_Client
	 */
	public function disableProfiler()
	{
		$this->profiler = false;
		return $this;
	}

	/**
	 * Sends a request and fetches a response from the server.
	 *
	 * @param string $method Method name
	 * @param array $params Method parameters
	 * @return mixed
	 * @throws BadMethodCallException If no server address was provided
	 * @throws Jyxo_Rpc_Exception On error
	 */
	abstract public function send($method, array $params);

	/**
	 * Processes request data and fetches response.
	 *
	 * @param string $contentType Request content-type
	 * @param string $data Request data
	 * @return string
	 * @throws BadMethodCallException If no server address was provided
	 * @throws Jyxo_Rpc_Exception On error
	 */
	protected function process($contentType, $data)
	{
		// Server address must be defined
		if (empty($this->url)) {
			throw new BadMethodCallException('No server address was provided.');
		}

		// Headers
		$headers = array(
			'Content-Type: ' . $contentType,
			'Content-Length: ' . strlen($data)
		);

		// Open a HTTP channel
		$channel = curl_init();
		curl_setopt($channel, CURLOPT_URL, $this->url);
		curl_setopt($channel, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($channel, CURLOPT_CONNECTTIMEOUT, 0);
		curl_setopt($channel, CURLOPT_TIMEOUT, $this->timeout);
		curl_setopt($channel, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($channel, CURLOPT_POSTFIELDS, $data);

		// Send a request
		$response = curl_exec($channel);

		// Error sending the request
		if (0 !== curl_errno($channel)) {
			$error = curl_error($channel);
			curl_close($channel);

			throw new Jyxo_Rpc_Exception($error);
		}

		// Wrong code
		$code = curl_getinfo($channel, CURLINFO_HTTP_CODE);
		if ($code >= 300) {
			$error = sprintf('Response error from %s, code %d.', $this->url, $code);
			curl_close($channel);

			throw new Jyxo_Rpc_Exception($error);
		}

		// Close the channel
		curl_close($channel);

		// Return the response
		return $response;
	}

	/**
	 * Starts profiling.
	 *
	 * @return Jyxo_Rpc_Client
	 */
	protected function profileStart()
	{
		// Set start time
		if ($this->profiler) {
			$this->timer = Jyxo_Timer::start();
		}

		return $this;
	}

	/**
	 * Finishes profiling.
	 *
	 * @param string $type Request type
	 * @param string $method Method name
	 * @param array $params Method parameters
	 * @param mixed $response Server response
	 * @return Jyxo_Rpc_Client
	 */
	protected function profileEnd($type, $method, array $params, $response)
	{
		// Profiling has to be turned on
		if ($this->profiler) {
			static $totalTime = 0;
			static $requests = array();

			// Get elapsed time
			$time = Jyxo_Timer::stop($this->timer);

			$totalTime += $time;
			$requests[] = array(strtoupper($type), (string) $method, $params, $response, sprintf('%0.3f', $time * 1000));

			// Send to FirePHP
			Jyxo_FirePhp::table(
				sprintf('Jyxo RPC Profiler (%d requests took %0.3f ms)', count($requests), sprintf('%0.3f', $totalTime * 1000)),
				array('Type', 'Method', 'Request', 'Response', 'Time'),
				$requests,
				'Rpc'
			);
		}

		return $this;
	}
}
