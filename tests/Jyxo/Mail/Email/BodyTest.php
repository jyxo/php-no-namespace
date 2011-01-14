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
 * Jyxo_Mail_Email_Body class test.
 *
 * @see Jyxo_Mail_Email_Body
 * @copyright Copyright (c) 2005-2011 Jyxo, s.r.o.
 * @license https://github.com/jyxo/php/blob/master/license.txt
 * @author Jaroslav Hanslík
 */
class Jyxo_Mail_Email_BodyTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Runs the test.
	 */
	public function test()
	{
		$html = file_get_contents(DIR_FILES . '/mail/email.html');
		$text = Jyxo_Html::toText($html);

		// HTML and plaintext given
		$body = new Jyxo_Mail_Email_Body($html, $text);
		$this->assertEquals($html, $body->getMain());
		$this->assertEquals($text, $body->getAlternative());
		$this->assertTrue($body->isHtml());

		// Only HTML
		$body = new Jyxo_Mail_Email_Body($html);
		$this->assertEquals($html, $body->getMain());
		$this->assertTrue($body->isHtml());

		// Only plaintext
		$body = new Jyxo_Mail_Email_Body($text);
		$this->assertEquals($text, $body->getMain());
		$this->assertFalse($body->isHtml());
	}
}
