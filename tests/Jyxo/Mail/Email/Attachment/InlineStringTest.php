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

require_once __DIR__ . '/../../../../bootstrap.php';

/**
 * Jyxo_Mail_Email_Attachment_InlineString class test.
 *
 * @see Jyxo_Mail_Email_Attachment_InlineString
 * @copyright Copyright (c) 2005-2011 Jyxo, s.r.o.
 * @license https://github.com/jyxo/php/blob/master/license.txt
 * @author Jaroslav Hanslík
 */
class Jyxo_Mail_Email_Attachment_InlineStringTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Runs the test.
	 */
	public function test()
	{
		$content = file_get_contents(DIR_FILES . '/mail/logo.gif');
		$name = 'logo.gif';
		$cid = 'logo.gif';
		$mimeType = 'image/gif';

		$attachment = new Jyxo_Mail_Email_Attachment_InlineString($content, $name, $cid, $mimeType);
		$this->assertEquals($content, $attachment->getContent());
		$this->assertEquals($name, $attachment->getName());
		$this->assertEquals($mimeType, $attachment->getMimeType());
		$this->assertEquals(Jyxo_Mail_Email_Attachment::DISPOSITION_INLINE, $attachment->getDisposition());
		$this->assertTrue($attachment->isInline());
		$this->assertEquals($cid, $attachment->getCid());
		$this->assertEquals('', $attachment->getEncoding());

		// It is possible to set an encoding
		$reflection = new ReflectionClass('Jyxo_Mail_Encoding');
		foreach ($reflection->getConstants() as $encoding) {
			$attachment->setEncoding($encoding);
			$this->assertEquals($encoding, $attachment->getEncoding());
		}

		// Incompatible encoding
		try {
			$attachment->setEncoding('dummy-encoding');
			$this->fail('Expected exception InvalidArgumentException.');
		} catch (PHPUnit_Framework_AssertionFailedError $e) {
			throw $e;
		} catch (Exception $e) {
			// Correctly thrown exception
			$this->assertInstanceOf('InvalidArgumentException', $e);
		}
	}
}
