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

require_once __DIR__ . '/../bootstrap.php';

/**
 * Test for the Jyxo_Css class.
 *
 * @see Jyxo_Css
 * @copyright Copyright (c) 2005-2011 Jyxo, s.r.o.
 * @license https://github.com/jyxo/php/blob/master/license.txt
 * @author Jaroslav Hanslík
 */
class Jyxo_CssTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Path to the files.
	 *
	 * @var string
	 */
	private $filePath;

	/**
	 * Prepares the testing environment.
	 */
	protected function setUp()
	{
		$this->filePath = DIR_FILES . '/css';
	}

	/**
	 * Tests the constructor.
	 *
	 * @see Jyxo_Css::__construct()
	 */
	public function testConstruct()
	{
		$this->setExpectedException('LogicException');
		$css = new Jyxo_Css();
	}

	/**
	 * Test the repair() method.
	 *
	 * @see Jyxo_Css::repair()
	 */
	public function testRepair()
	{
		// In the form: expected css, given css
		$tests = array();

		// Converts property names to lowercase
		$tests[] = array('html { margin : 10px 20px 10px; color: black}', 'html { MARGIN : 10px 20px 10px; COLOR: black}');
		$tests[] = array('margin: 10px 20px 10px; color: black;', 'MARGIN: 10px 20px 10px; COLOR: black;');

		// Converts rgb() and url() to lowercase
		$tests[] = array('background: url (\'background.png\') #ffffff;' , 'background: URL (\'background.png\') RGB (255, 255, 255);');

		// Remove properties without definitions
		$tests[] = array('border: solid 1px black;', 'border: solid 1px black; color:; color:; color:');
		$tests[] = array('border: solid 1px black;', 'border: solid 1px black; color: ;');
		$tests[] = array('border: solid 1px black;', 'border: solid 1px black; color:');
		$tests[] = array('{border: solid 1px black;}', '{border: solid 1px black; color: }');
		$tests[] = array('{border: solid 1px black; } ', '{border: solid 1px black; color : ; } ');

		// Remove MS Word properties
		$tests[] = array('{}', '{mso-bidi-font-weight: normal; mso-bidi-font-weight: normal; mso-ascii-theme-font: minor-latin; mso-fareast-font-family: Calibri; mso-ansi-language: CS; mso-hansi-theme-font: minor-latin;}');
		$tests[] = array('{color: black;}', '{color: black; mso-bidi-font-weight: normal}');
		$tests[] = array('{color: black;}', '{color: black; mso-bidi-font-weight : }');
		$tests[] = array('color: black;', 'color: black; mso-bidi-font-weight:');

		// Converts colors to lowercase
		$tests[] = array('color: #aabbcc;', 'color: #aaBBcc;');
		$tests[] = array('color: #aabbcc', 'color: #aabbcc');
		$tests[] = array('color:#aa00cc;', 'color:#Aa00Cc;');

		// Converts color from RGB to HEX
		$tests[] = array('color: #ffffff;', 'color: rgb(255, 255, 255);');
		$tests[] = array('color:#000000', 'color:rgb (0,0,0)');
		$tests[] = array('color: #a4a2a3;', 'color: RGB( 164 , 162 , 163 );');

		foreach ($tests as $no => $test) {
			$this->assertEquals($test[0], Jyxo_Css::repair($test[1]), sprintf('Test %s', $no + 1));
		}
	}

	/**
	 * Tests the filterProperties() method.
	 *
	 * @see Jyxo_Css::filterProperties()
	 */
	public function testFilterProperties()
	{
		// Filters given properties
		$this->assertEquals(
			'{border: solid 1px black; padding: 10px;}',
			Jyxo_Css::filterProperties('{border: solid 1px black; color: black; padding: 10px;}', array('color'))
		);
		$this->assertEquals(
			'border:solid 1px black;padding:10px',
			Jyxo_Css::filterProperties('border:solid 1px black;color:black;padding:10px', array('color'))
		);
		$this->assertEquals(
			'border:solid 1px black;',
			Jyxo_Css::filterProperties('border:solid 1px black;padding:10px', array('padding'))
		);
		$this->assertEquals(
			'{border:solid 1px black}',
			Jyxo_Css::filterProperties('{padding:10px;border:solid 1px black}', array('padding'))
		);
		$this->assertEquals(
			'{}',
			Jyxo_Css::filterProperties('{color: #000000; padding: 10px; border: solid 1px black;}', array('color', 'border', 'padding'))
		);

		// Keeps given properties and keeps everything else
		$this->assertEquals(
			'{ color: black;}',
			Jyxo_Css::filterProperties('{border: solid 1px black; color: black; padding: 10px;}', array('color'), false)
		);
		$this->assertEquals(
			'color:black;',
			Jyxo_Css::filterProperties('border:solid 1px black;color:black;padding:10px', array('color'), false)
		);
		$this->assertEquals(
			'padding:10px',
			Jyxo_Css::filterProperties('border:solid 1px black;padding:10px', array('padding'), false)
		);
		$this->assertEquals(
			'{padding:10px;}',
			Jyxo_Css::filterProperties('{padding:10px;border:solid 1px black}', array('padding'), false)
		);
		$this->assertEquals(
			'{color: #000000; padding: 10px; border: solid 1px black;}',
			Jyxo_Css::filterProperties('{color: #000000; padding: 10px; border: solid 1px black;}', array('color', 'border', 'padding'), false)
		);
	}

	/**
	 * Tests the pack() method.
	 *
	 * @see Jyxo_Css::pack()
	 */
	public function testPack()
	{
		$this->assertStringEqualsFile(
			$this->filePath . '/pack-expected.css',
			Jyxo_Css::pack(file_get_contents($this->filePath . '/pack.css'))
		);

		// Color conversion
		$this->assertEquals(
			'background:url(\'image.png\') #000 top left;color:#999;border:solid 1px #cfc',
			Jyxo_Css::pack('background: url( \'image.png\' ) #000000 top left; color: #999999; border: solid 1px #ccffcc;')
		);
	}

	/**
	 * Tests the convertStyleToInline() method.
	 *
	 * @see Jyxo_Css::convertStyleToInline()
	 */
	public function testConvertStyleToInline()
	{
		$testCount = 6;

		for ($i = 1; $i <= $testCount; $i++) {
			$this->assertStringEqualsFile(
				$this->filePath . '/' . sprintf('convertstyle-%s-expected.html', $i),
				Jyxo_Css::convertStyleToInline(file_get_contents($this->filePath . '/' . sprintf('convertstyle-%s.html', $i))),
				sprintf('Failed test %s for method Jyxo_Css::convertStyleToInline.', $i)
			);
		}
	}
}
