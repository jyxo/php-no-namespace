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

require_once __DIR__ . '/../../../bootstrap.php';

/**
 * Test for class Jyxo_Rpc_Json_Server.
 *
 * @see Jyxo_Rpc_Json_Server
 * @copyright Copyright (c) 2005-2011 Jyxo, s.r.o.
 * @license https://github.com/jyxo/php/blob/master/license.txt
 * @author Jan Pěček
 * @author Jaroslav Hanslík
 */
class Jyxo_Rpc_Json_ServerTest extends Jyxo_Rpc_ServerTestCase
{
	/**
	 * Returns server instance.
	 *
	 * @return Jyxo_Rpc_Server
	 */
	protected function getServerInstance()
	{
		return Jyxo_Rpc_Json_Server::getInstance();
	}

	/**
	 * Returns test files extension.
	 *
	 * @return string
	 */
	protected function getFileExtension()
	{
		return 'json';
	}
}
