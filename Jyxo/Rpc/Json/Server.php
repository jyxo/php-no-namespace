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
 * Class for creating a JSON-RPC server.
 *
 * @category Jyxo
 * @package Jyxo_Rpc
 * @subpackage Json
 * @copyright Copyright (c) 2005-2011 Jyxo, s.r.o.
 * @license https://github.com/jyxo/php/blob/master/license.txt
 * @author Jan Pěček
 */
class Jyxo_Rpc_Json_Server extends Jyxo_Rpc_Server
{
	/**
	 * Definition of error codes and appropriate error messages.
	 *
	 * @var array
	 */
	private static $jsonErrors = array(
		JSON_ERROR_DEPTH => 'Maximum stack depth exceeded.',
		JSON_ERROR_CTRL_CHAR => 'Unexpected control character found.',
		JSON_ERROR_SYNTAX => 'Syntax error, malformed JSON.'
	);

	/**
	 * List of registered methods.
	 *
	 * @var array
	 */
	private $methods = array();

	/**
	 * Actually registers a function to a server method.
	 *
	 * @param string $func Function definition
	 */
	protected function register($func)
	{
		$this->methods[] = $func;
	}

	/**
	 * Processes a request and sends a JSON-RPC response.
	 */
	public function process()
	{
		$requestId = '';
		try {
			$data = file_get_contents('php://input');
			$data = trim($data);
			if (empty($data)) {
				throw new Jyxo_Rpc_Json_Exception('No data received.', -32700);
			}
			$data = json_decode($data, true);

			// Request decoding error
			if ($data === null && ($faultCode = json_last_error()) != JSON_ERROR_NONE) {
				throw new Jyxo_Rpc_Json_Exception(self::$jsonErrors[$faultCode], $faultCode);
			}

			$requestId = isset($data['id']) ? $data['id'] : '';

			// Parsing request data error
			if (empty($data['method']) || !isset($data['id'])) {
				throw new Jyxo_Rpc_Json_Exception('Parse error.', 10);
			}

			// Non-existent method call
			if (!in_array($data['method'], $this->methods)) {
				throw new Jyxo_Rpc_Json_Exception('Server error. Method not found.', -32601);
			}

			// Request processing
			$params = !empty($data['params']) ? (array) $data['params'] : array();
			$response = $this->call($data['method'], $params);
			$response = array('result' => $response, 'id' => $data['id']);

		} catch (Jyxo_Rpc_Json_Exception $e) {
			$response = array(
				'error' => array(
					'message' => $e->getMessage(),
					'code' => $e->getCode()
				),
				'id' => $requestId
			);
		}

		header('Content-Type: application/json; charset="utf-8"');
		echo json_encode($response);
	}
}
