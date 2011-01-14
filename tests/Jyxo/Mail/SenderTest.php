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

require_once __DIR__ . '/../../bootstrap.php';

/**
 * Jyxo_Mail_Sender class test.
 *
 * @see Jyxo_Mail_Sender
 * @copyright Copyright (c) 2005-2011 Jyxo, s.r.o.
 * @license https://github.com/jyxo/php/blob/master/license.txt
 * @author Jaroslav Hanslík
 */
class Jyxo_Mail_SenderTest extends PHPUnit_Framework_TestCase
{
	/**
	 * File path.
	 *
	 * @var string
	 */
	private $filePath;

	/**
	 * Testing mail contents.
	 *
	 * @var string
	 */
	private $content;

	/**
	 * Prepares the testing environment.
	 */
	protected function setUp()
	{
		$this->filePath = DIR_FILES . '/mail';
		$this->content = file_get_contents($this->filePath . '/email.html');
	}

	/**
	 * Tests sending using the mail() function.
	 */
	public function testSendMail()
	{
		$sender = new Jyxo_Mail_Sender();
		$sender->setEmail($this->getEmail());
		$result = $sender->send(Jyxo_Mail_Sender::MODE_MAIL);
		$this->assertResult('sender-type-simple-text.eml', $result);
	}

	/**
	 * Tests sending using a SMTP server.
	 */
	public function testSendSmtp()
	{
		// Skips the test if no smtp connection is set
		if (empty($GLOBALS['smtp'])) {
			$this->markTestSkipped('Smtp host not set');
		}

		$sender = new Jyxo_Mail_Sender();
		$sender->setEmail($this->getEmail())
			->setSmtp($GLOBALS['smtp']);
		$result = $sender->send(Jyxo_Mail_Sender::MODE_SMTP);
		$this->assertResult('sender-type-simple-text.eml', $result);
	}

	/**
	 * Tests possible sending errors.
	 */
	public function testSendErrors()
	{
		$email = new Jyxo_Mail_Email();
		$email->setBody(new Jyxo_Mail_Email_Body(''));
		$sender = new Jyxo_Mail_Sender();
		$sender->setEmail($email);

		// Non-existent sending method
		try {
			$sender->send('dummy-mode');
			$this->fail('Expected exception InvalidArgumentException.');
		} catch (PHPUnit_Framework_AssertionFailedError $e) {
			throw $e;
		} catch (Exception $e) {
			// Correctly thrown exception
			$this->assertInstanceOf('InvalidArgumentException', $e);
		}

		// Missing sender
		try {
			$sender->send(Jyxo_Mail_Sender::MODE_NONE);
			$this->fail('Expected exception Jyxo_Mail_Sender_CreateException.');
		} catch (PHPUnit_Framework_AssertionFailedError $e) {
			throw $e;
		} catch (Exception $e) {
			// Correctly thrown exception
			$this->assertInstanceOf('Jyxo_Mail_Sender_CreateException', $e);
		}

		$email->setFrom(new Jyxo_Mail_Email_Address('blog-noreply@blog.cz'));

		// Missing recipients
		try {
			$sender->send(Jyxo_Mail_Sender::MODE_NONE);
			$this->fail('Expected exception Jyxo_Mail_Sender_CreateException.');
		} catch (PHPUnit_Framework_AssertionFailedError $e) {
			throw $e;
		} catch (Exception $e) {
			// Correctly thrown exception
			$this->assertInstanceOf('Jyxo_Mail_Sender_CreateException', $e);
		}

		$email->addTo(new Jyxo_Mail_Email_Address('test@blog.cz'));

		// Empty body
		try {
			$sender->send(Jyxo_Mail_Sender::MODE_NONE);
			$this->fail('Expected exception Jyxo_Mail_Sender_CreateException.');
		} catch (PHPUnit_Framework_AssertionFailedError $e) {
			throw $e;
		} catch (Exception $e) {
			// Correctly thrown exception
			$this->assertInstanceOf('Jyxo_Mail_Sender_CreateException', $e);
		}
	}

	/**
	 * Tests a complete email message with all settings.
	 */
	public function testCompleteEmail()
	{
		// Email
		$from = new Jyxo_Mail_Email_Address('blog-noreply@blog.cz', 'Blog.cz');
		$email = $this->getEmail();
		$email->setFrom($from)
			->addReplyTo($from)
			->setPriority(Jyxo_Mail_Email::PRIORITY_NORMAL)
			->setInReplyTo('161024ac03484c10203285be576446f2@blog.cz', array('30d6c4933818e36fa46509ad24a91ea4@blog.cz', '8b30935de59b6c89e4fc1204d279a2af@blog.cz'))
			->setConfirmReadingTo($from)
			->addHeader(new Jyxo_Mail_Email_Header('Organization', 'Blog.cz'))
			->addTo(new Jyxo_Mail_Email_Address('test2@blog.cz'))
			->addCc(new Jyxo_Mail_Email_Address('test3@blog.cz', 'Test Test3'))
			->addCc(new Jyxo_Mail_Email_Address('test4@blog.cz'))
			->addBcc(new Jyxo_Mail_Email_Address('test5@blog.cz', 'Příliš žluťoučký kůň'));

		// Charset
		$sender = new Jyxo_Mail_Sender();
		$charset = 'iso-8859-2';
		$sender->setCharset($charset);
		$this->assertEquals($charset, $sender->getCharset());

		// X-mailer
		$xmailer = 'Blog.cz';
		$sender->setXmailer($xmailer);
		$this->assertEquals($xmailer, $sender->getXmailer());

		// Hostname
		$this->assertEquals('localhost', $sender->getHostname());
		$hostname = 'blog.cz';
		$sender->setHostname($hostname);
		$this->assertEquals($hostname, $sender->getHostname());

		// Encoding
		$reflection = new ReflectionClass('Jyxo_Mail_Encoding');
		foreach ($reflection->getConstants() as $encoding) {
			$sender->setEncoding($encoding);
			$this->assertEquals($encoding, $sender->getEncoding());
		}
		try {
			$sender->setEncoding('dummy-encoding');
			$this->fail('Expected exception InvalidArgumentException.');
		} catch (PHPUnit_Framework_AssertionFailedError $e) {
			throw $e;
		} catch (Exception $e) {
			// Correctly thrown exception
			$this->assertInstanceOf('InvalidArgumentException', $e);
		}

		// Email
		$sender->setEmail($email);
		$this->assertSame($email, $sender->getEmail());

		// Sending
		$sender->setEncoding(Jyxo_Mail_Encoding::BASE64);
		$result = $sender->send(Jyxo_Mail_Sender::MODE_NONE);

		$this->assertResult('sender-complete.eml', $result);
	}

	/**
	 * Tests all email types (with attachments, without, ...).
	 */
	public function testAllTypes()
	{
		// Sender
		$sender = new Jyxo_Mail_Sender();

		// HTML email without attachments
		$email = $this->getEmail()
			->setBody(new Jyxo_Mail_Email_Body($this->content));
		$sender->setEmail($email);
		$result = $sender->send(Jyxo_Mail_Sender::MODE_NONE);

		$this->assertResult('sender-type-simple-html.eml', $result);

		// Plaintext email without attachments
		$email->setBody(new Jyxo_Mail_Email_Body(Jyxo_Html::toText($this->content)));
		$sender->setEmail($email);
		$result = $sender->send(Jyxo_Mail_Sender::MODE_NONE);

		$this->assertResult('sender-type-simple-text.eml', $result);

		// HTML email with attachments
		$email = $this->getEmail()
			->setBody(new Jyxo_Mail_Email_Body($this->content))
			->addAttachment(new Jyxo_Mail_Email_Attachment_File($this->filePath . '/logo.gif', 'logo.gif', 'image/gif'))
			->addAttachment(new Jyxo_Mail_Email_Attachment_String(file_get_contents($this->filePath . '/star.gif'), 'star.gif', 'image/gif'));
		$sender->setEmail($email);
		$result = $sender->send(Jyxo_Mail_Sender::MODE_NONE);

		$this->assertResult('sender-type-attachment-html.eml', $result);

		// Plaintext email with attachments
		$email->setBody(new Jyxo_Mail_Email_Body(Jyxo_Html::toText($this->content)));
		$sender->setEmail($email);
		$result = $sender->send(Jyxo_Mail_Sender::MODE_NONE);

		$this->assertResult('sender-type-attachment-text.eml', $result);

		// Email with an alternative content
		$email = $this->getEmail()
			->setBody(new Jyxo_Mail_Email_Body($this->content, Jyxo_Html::toText($this->content)));
		$sender->setEmail($email);
		$result = $sender->send(Jyxo_Mail_Sender::MODE_NONE);

		$this->assertResult('sender-type-alternative.eml', $result);

		// Email with an alternative content and inline attachments
		$email->addAttachment(new Jyxo_Mail_Email_Attachment_InlineFile($this->filePath . '/logo.gif', 'logo.gif', 'logo.gif', 'image/gif'))
			->addAttachment(new Jyxo_Mail_Email_Attachment_InlineString(file_get_contents($this->filePath . '/star.gif'), 'star.gif', 'star.gif', 'image/gif'));
		$sender->setEmail($email);
		$result = $sender->send(Jyxo_Mail_Sender::MODE_NONE);

		$this->assertResult('sender-type-alternative-attachment.eml', $result);
	}

	/**
	 * Tests creating an email with only Bcc recipients.
	 */
	public function testUndisclosedRecipients()
	{
		$email = new Jyxo_Mail_Email();
		$email->setSubject('Test')
			->setFrom(new Jyxo_Mail_Email_Address('blog-noreply@blog.cz', 'Blog.cz'))
			->setBody(new Jyxo_Mail_Email_Body('Test'))
			->addBcc(new Jyxo_Mail_Email_Address('test@blog.cz', 'Test Test'));

		$sender = new Jyxo_Mail_Sender();
		$sender->setEmail($email);
		$result = $sender->send(Jyxo_Mail_Sender::MODE_NONE);

		$this->assertResult('sender-undisclosed-recipients.eml', $result);
	}

	/**
	 * Creates a basic email.
	 *
	 * @return Jyxo_Mail_Email
	 */
	private function getEmail()
	{
		$email = new Jyxo_Mail_Email();
		$email->setSubject('Novinky září 2009 ... a kreslící soutěž')
			->setFrom(new Jyxo_Mail_Email_Address('blog-noreply@blog.cz', 'Blog.cz'))
			->addTo(new Jyxo_Mail_Email_Address('test@blog.cz', 'Test Test'))
			->setBody(new Jyxo_Mail_Email_Body(Jyxo_Html::toText($this->content)));

		return $email;
	}

	/**
	 * Compares the actual and expected result.
	 *
	 * @param string $file File with the expected result
	 * @param Jyxo_Mail_Sender_Result $result
	 */
	private function assertResult($file, Jyxo_Mail_Sender_Result $result)
	{
		$expected = file_get_contents($this->filePath . '/' . $file);

		// Replacing some headers that are created dynamically
		$expected = preg_replace('~====b1[a-z0-9]{32}====~', '====b1' . substr($result->messageId, 0, 32) . '====', $expected);
		$expected = preg_replace('~====b2[a-z0-9]{32}====~', '====b2' . substr($result->messageId, 0, 32) . '====', $expected);
		$expected = preg_replace("~Date: [^\n]+~", 'Date: ' . $result->datetime->email, $expected);
		$expected = preg_replace('~Message-ID: <[^>]+>~', 'Message-ID: <' . $result->messageId . '>', $expected);

		$this->assertEquals($expected, $result->source, sprintf('Failed test for file %s.', $file));
	}
}
