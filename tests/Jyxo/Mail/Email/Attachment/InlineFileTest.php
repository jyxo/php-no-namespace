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
 * Jyxo_Mail_Email_Attachment_InlineFile class test.
 *
 * @see Jyxo_Mail_Email_Attachment_InlineFile
 * @copyright Copyright (c) 2005-2011 Jyxo, s.r.o.
 * @license https://github.com/jyxo/php/blob/master/license.txt
 * @author Jaroslav Hanslík
 */
class Jyxo_Mail_Email_Attachment_InlineFileTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Runs the test.
	 */
	public function test()
	{
		$path = DIR_FILES . '/mail/logo.gif';
		$name = 'logo.gif';
		$cid = 'logo.gif';
		$mimeType = 'image/gif';

		$attachment = new Jyxo_Mail_Email_Attachment_InlineFile($path, $name, $cid, $mimeType);
		$this->assertEquals(file_get_contents($path), $attachment->getContent());
		$this->assertEquals($name, $attachment->getName());
		$this->assertEquals($mimeType, $attachment->getMimeType());
		$this->assertEquals(Jyxo_Mail_Email_Attachment::DISPOSITION_INLINE, $attachment->getDisposition());
		$this->assertTrue($attachment->isInline());
		$this->assertEquals($cid, $attachment->getCid());
		$this->assertEquals('', $attachment->getEncoding());
	}
}
