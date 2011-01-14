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
 * Class for sending requests using JSON-RPC.
 * Requires json and curl PHP extensions.
 *
 * @category Jyxo
 * @package Jyxo_Rpc
 * @subpackage Json
 * @copyright Copyright (c) 2005-2011 Jyxo, s.r.o.
 * @license https://github.com/jyxo/php/blob/master/license.txt
 * @author Jan Pěček
 */
class Jyxo_Rpc_Json_Client extends Jyxo_Rpc_Client
{
	/**
	 * Sends a request and fetches server's response.
	 *
	 * @param string $method Method name
	 * @param array $params Method parameters
	 * @return mixed
	 * @throws BadMethodCallException If no server address was provided
	 * @throws Jyxo_Rpc_Json_Exception On error
	 */
	public function send($method, array $params)
	{
		// Start profiling
		$this->profileStart();

		try {
			// Prepare JSON-RPC request
			$data = json_encode(
				array(
					'request' => array(
						'method' => $method,
						'params' => $params
					)
				)
			);

			// Fetch response
			$response = $this->process('application/json', $data);

			// Process response
			$response = json_decode($response, true);

		} catch (Jyxo_Rpc_Exception $e) {
			// Finish profiling
			$this->profileEnd('JSON', $method, $params, $e->getMessage());

			throw new Jyxo_Rpc_Json_Exception($e->getMessage(), 0, $e);
		}

		// Finish profiling
		$this->profileEnd('JSON', $method, $params, $response);

		// Error in response
		if (!is_array($response) || !isset($response['response'])) {
			throw new Jyxo_Rpc_Json_Exception('Nebyl navrácen požadovaný formát dat.');
		}
		$response = $response['response'];
		if ((is_array($response)) && (isset($response['fault']))) {
			throw new Jyxo_Rpc_Json_Exception(preg_replace('~\s+~', ' ', $response['fault']['faultString']));
		}

		return $response;
	}
}
