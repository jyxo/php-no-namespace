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
require_once __DIR__ . '/../../files/input/Validator.php';

/**
 * Tests of Jyxo_Input package validators.
 *
 * @copyright Copyright (c) 2005-2011 Jyxo, s.r.o.
 * @license https://github.com/jyxo/php/blob/master/license.txt
 * @author Jan Pěček
 * @author Jaroslav Hanslík
 * @author Ondřej Nešpor
 */
class Jyxo_Input_ValidatorTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Validator error code.
	 *
	 * @var integer
	 */
	const ERROR_CODE = 42;

	/**
	 * Validator error message.
	 *
	 * @var string
	 */
	const ERROR_MESSAGE = 'Error!';

	/**
	 * Tests Tests InArray validator.
	 */
	public function testInArray()
	{
		$values = array(1, 2, '3', null, 'foo');

		$good = array(
			1,
			null,
			true,
			false,
			'',
			'2',
			'foo'
		);
		$wrong = array(
			'bah',
			4,
			1.5
		);

		$this->executeTests(new Jyxo_Input_Validator_InArray($values), $good, $wrong);
	}

	/**
	 * Tests Tests IsArray validator.
	 */
	public function testIsArray()
	{
		$good = array(
			array('test')
		);
		$wrong = array(
			'test',
			123,
			1.5,
			true
		);

		$this->executeTests(new Jyxo_Input_Validator_IsArray(), $good, $wrong);
	}

	/**
	 * Tests IsBankAccountNumber validator.
	 */
	public function testIsBankAccountNumber()
	{
		$good = array(
			'000000-0145254386/2400',
			'000019-2000145399/0800',
			'19-2000145399/0800',
			'145254386/2400'
		);
		$wrong = array(
			'18-2000145399/0800',
			'000000-0145254386',
			'000000-014525438/2400',
			'000000-0145254386/0000',
			array(),
			10
		);

		$this->executeTests(new Jyxo_Input_Validator_IsBankAccountNumber(), $good, $wrong);
	}

	/**
	 * Tests IsBirthNumber validator.
	 */
	public function testIsBirthNumber()
	{
		$good = array(
			'8203050218',
			'820305/0218',
			'820305 0218',
			'340621/026',
			'840501/1330',
			'0681186066',
			'0531135099',
			'345514/1360'
		);
		$wrong = array(
			'820305 0219',
			'820332/0218',
			'820305-0218',
			'670229/1125',
			'670229/1145',
			'540621/026',
			array(),
			10
		);

		$this->executeTests(new Jyxo_Input_Validator_IsBirthNumber(), $good, $wrong);
	}

	/**
	 * Tests IsCompanyId validator.
	 */
	public function testIsCompanyId()
	{
		$good = array(
			'26704706',
			'27401944',
			'25596641',
			'14800381',
			'47782170'
		);
		$wrong = array(
			'267047',
			'26704705',
			array(),
			10
		);

		$this->executeTests(new Jyxo_Input_Validator_IsCompanyId(), $good, $wrong);
	}

	/**
	 * Tests IsCountryCode validator.
	 */
	public function testIsCountryCode()
	{
		$good = $this->getGoodValues(Jyxo_Input_Validator_IsCountryCode::getCountries());
		$wrong = array(
			'A',
			'B',
			1,
			0
		);

		$this->executeTests(new Jyxo_Input_Validator_IsCountryCode(), $good, $wrong);
	}

	/**
	 * Tests IsDate validator.
	 */
	public function testIsDate()
	{
		$good = array(
			'1993-01-01',
			'2000-02-29',
			date('Y-m-d')
		);
		$wrong = array(
			23,
			'2009-02-29'
		);

		$this->executeTests(new Jyxo_Input_Validator_IsDate(), $good, $wrong);
	}


	/**
	 * Tests IsDateTime validator.
	 */
	public function testIsDateTime()
	{
		$good = array(
			'1993-01-01 01:00:00',
			'2000-02-29 15:23:59',
			date('Y-m-d H:i:s')
		);
		$wrong = array(
			23,
			'2009-02-29 01:00:00',
			'2009-02-28 24:00:00',
			'2009-02-28 23:60:00',
			'2009-02-28 23:59:60'
		);

		$this->executeTests(new Jyxo_Input_Validator_IsDateTime(), $good, $wrong);
	}

	/**
	 * Tests IsEmail validator.
	 */
	public function testIsEmail()
	{
		$good = array(
			'test@jyxo.com',
			'velky.ohromny.test@jyxo.blog.cz',
			'123@jyxo.com',
			'ZahradyR+R@email.cz'
		);
		$wrong = array(
			'česko@jyxo.com',
			'test test@jyxo.com',
			'test.jyxo.com',
			'test@test@jyxo.com',
			'test.@jyxo.com',
			'.test@jyxo.com',
			'test..test@jyxo.com'
		);

		$this->executeTests(new Jyxo_Input_Validator_IsEmail(), $good, $wrong);
	}

	/**
	 * Tests IsIban validator.
	 */
	public function testIsIban()
	{
		$good = array(
			'CZ65 0800 0000 1920 0014 5399',
			'CZ09 0800 0000 0003 5349 7163',
			'CZ3208000000000000007894',
			'CZ23 0300 0000 0001 2708 9559',
			'CZ50 0400 0000 0042 3781 9004',
			'CZ 50 0600 0000 0001 7374 6388',
			'CZ 12 0300 0000 0006 0095 1053',
			'CZ 6624000000000137641001'
		);
		$wrong = array(
			'CZ09 0000 0000 0003 5349 7163',
			'CZ65 0800 0000 1820 0014 5399',
			'CZ65 0800 0000 1921 0014 5399',
			'CZ66 0800 0000 1920 0014 5399',
			'SK3208000000000000007894'
		);

		$this->executeTests(new Jyxo_Input_Validator_IsIban(), $good, $wrong);
	}

	/**
	 * Tests IsInt validator.
	 */
	public function testIsInt()
	{
		$good = array('1', 1, '0', 0, '12345');
		$wrong = array('0xFF', '+0123.45e6');

		$this->executeTests(new Jyxo_Input_Validator_IsInt(), $good, $wrong);
	}

	/**
	 * Tests IsIpV4 validator.
	 */
	public function testIsIpV4()
	{
		$good = array(
			'127.0.0.1',
			'192.168.24.0',
			'217.31.54.133',
			'10.0.0.71',
			'212.47.13.165',
			'89.235.3.140',
			'147.32.127.214'
		);
		$wrong = array('999.999.999.999');

		$this->executeTests(new Jyxo_Input_Validator_IsIpV4(), $good, $wrong);
	}

	/**
	 * Tests IsIpV6 validator.
	 */
	public function testIsIpV6()
	{
		$good = array(
			'2001:0db8:0000:0000:0000:0000:1428:57ab',
			'2001:0db8:0000:0000:0000::1428:57ab',
			'2001:0db8:0:0:0:0:1428:57ab',
			'2001:0db8:0:0::1428:57ab',
			'2001:0db8::1428:57ab',
			'2001:db8::1428:57ab',
			'2001:0db8:85a3:08d3:1319:8a2e:0370:7344',
			'2001:0718:1c01:0016:0214:22ff:fec9:0ca5',
			'2001:718:1c01:16:214:22ff:fec9:ca5',
			'ff02:0:0:0:0:0:0:1'
		);

		$wrong = array(
			'xx02:db8::1428:57ab',
			'2001:0xx8:0:0:0:0:1428:57yy'
		);

		$this->executeTests(new Jyxo_Input_Validator_IsIpV6(), $good, $wrong);
	}

	/**
	 * Tests IsNumeric validator.
	 */
	public function testIsNumeric()
	{
		$good = array('1', 1, '0', 0, '42', '1e4', 9.1, '9.1', '+0123.45e6', '-12.2e-6', '0xFF', 0xFF);
		$wrong = array('not numeric', array(), '123a4', '9,1', '0xBW');

		$this->executeTests(new Jyxo_Input_Validator_IsNumeric(), $good, $wrong);
	}

	/**
	 * Tests IsPhone validator.
	 */
	public function testIsPhone()
	{
		$good = array(
			'112',
			'1188',
			'11233',
			'800123456',
			'800 02 02 02',
			'223456789',
			'+420223456789',
			'00420223456789',
			'223 456 789',
			'223 45 67 89'
		);
		$wrong = array(
			'1',
			'22345678',
			'-420223456789',
			'+420800020202',	// 8-number must not have a pre-dial
			'0420123456789'
		);

		$this->executeTests(new Jyxo_Input_Validator_IsPhone(), $good, $wrong);
	}

	/**
	 * Tests IsTaxId validator.
	 */
	public function testIsTaxId()
	{
		$good = array(
			'CZ 26704706',
			'267-26704706',
			'CZ 8405011330'
		);
		$wrong = array(
			'SK12345678',
			'CZ 8405011328'
		);

		$this->executeTests(new Jyxo_Input_Validator_IsTaxId(), $good, $wrong);

		// Try the so called "own numbers"
		$taxId = 'CZ12345678';
		$this->executeTests(new Jyxo_Input_Validator_IsTaxId(false), array($taxId), array());
	}

	/**
	 * Tests IsUrl validator.
	 */
	public function testIsUrl()
	{
		$good = array(
			'http://jyxo.com',
			'http://www.jyxo.com',
			'www.jyxo.cz',
			'jyxo.blog.cz',
			'http://www.google.cz/search?hl=cs&q=jyxo&btnG=Hledat&lr',
			'https://www.google.com/accounts/ServiceLogin?hl=en&continue=http://www.google.com/history/welcome%3Fhl%3Den%26zx%3DUZHUZwVdsQQ&nui=1&service=hist',
			'http://cybex.cz/Produkt.aspx?Shortcut=FPC-202167&Type=K&CategoryId=0',
			'http://www.dobryweb.cz/skoleni-google-adwords/?seo',
			'http://cs.wikipedia.org/wiki/%C5%98%C3%ADmsk%C3%A9_%C4%8D%C3%ADslice#Nula',
			'http://lidovky.zpravy.cz/hlasujte-co-je-nejosklivejsi-di6-/ln_redakce.asp?c=A070420_194521_ln_redakce_znk',
			'http://wiki.jyxo.com/index.php/Hlavn%C3%AD_strana',
			'http://meta.wikimedia.org/wiki/Logo#Proposing_new_logos',
			'http://firma.aspi.cz:5180/cgi/query?d=ai&ic=utf-8&oc=utf-8&timeout=30&s=%2A+%24source%3D6+%24product%3D2&cnt=5&sort=0&ctsize=200&o=stemhint',
			'http://feed.com.br/feex!2.xml',
			'http://www.example.com/',
			'http://user:password@example.com/test/index.html',
			'www.example.com/test/index.html',
			'http://example.com',
			'http://www.o.k.navy.cz',
			'http://www.youtube.com/v/I5ewQSfrn9Q&hl=cs&fs=1&',
			'http://www.youtube.com/watch#!v=7gcxnoA9K0k&feature=related',
			'http://image.tn.nova.cz/media/images///600x277/May2009//505002.jpg'
		);
		$wrong = array(
			'http://www.džikso.cz/',
			'htt://www.jyxo.cz/',
			'http://www.jyxo.čz/',
			'http://a.cz',
			'http://www.a.cz',
			'http://domain._abc.com'
		);

		$this->executeTests(new Jyxo_Input_Validator_IsUrl(), $good, $wrong);
	}

	/**
	 * Tests IsZipCode validator.
	 */
	public function testIsZipCode()
	{
		$good = array(
			'14000',
			'140 00'
		);
		$wrong = array(
			'1400a',
			'1400'
		);

		$this->executeTests(new Jyxo_Input_Validator_IsZipCode(), $good, $wrong);
	}


	/**
	 * Tests LessThan validator.
	 */
	public function testLessThan()
	{
		$good = array(
			0,
			10,
			-10,
			'-100'
		);
		$wrong = array(
			101,
			'102'
		);

		$validator = new Jyxo_Input_Validator_LessThan(100);
		$this->executeTests($validator, $good, $wrong);
		$this->assertEquals(100, $validator->getMax());
	}

	/**
	 * Tests Equals validator.
	 */
	public function testEquals()
	{
		$expected = 123;
		$good = array(
			123,
			'123',
			true
		);
		$bad = array(
			12,
			'A123',
			false
		);

		$validator = new Jyxo_Input_Validator_Equals($expected);

		$this->executeTests($validator, $good, $bad);
		$this->assertSame($expected, $validator->getExpected());
	}

	/**
	 * Tests NotEmpty validator.
	 */
	public function testNotEmpty()
	{
		$good = array(
			'NULL',
			array(0)
		);
		$wrong = array(
			'',
			0,
			'0',
			array(),
			null
		);

		$this->executeTests(new Jyxo_Input_Validator_NotEmpty(), $good, $wrong);
	}

	/**
	 * Tests Regex validator.
	 */
	public function testRegex()
	{
		$good = array(
			'test',
			'123',
			'JYXO'
		);
		$wrong = array(
			'test-test',
			'$test',
			'--',
			'..//'
		);
		$pattern = '~^\w+$~i';

		$validator = new Jyxo_Input_Validator_Regex($pattern);
		$this->executeTests($validator, $good, $wrong);
		$this->assertEquals($pattern, $validator->getPattern());

		$this->setExpectedException('Jyxo_Input_Validator_Exception');
		$validator->setPattern('');
	}

	/**
	 * Tests StringLengthGraterThan validator.
	 */
	public function testStringLengthGreaterThan()
	{
		$good = array(
			'test-test-test',
			'ěščřžýáíéíáýž'
		);
		$wrong = array(
			'žlutý',
			'test'
		);
		$length = 10;

		$validator = new Jyxo_Input_Validator_StringLengthGreaterThan($length);
		$this->executeTests($validator, $good, $wrong);
		$this->assertEquals($length, $validator->getMin());

		try {
			$validator->setMin(-10);
			$this->fail('Expected exception InvalidArgumentException.');
		} catch (PHPUnit_Framework_AssertionFailedError $e) {
			throw $e;
		} catch (Exception $e) {
			// Correctly thrown exception
			$this->assertInstanceOf('InvalidArgumentException', $e);
		}
	}

	/**
	 * Tests StringLengthLessThan validator.
	 */
	public function testStringLengthLessThan()
	{
		$good = array(
			'žlutý',
			'test'
		);
		$wrong = array(
			'test-test-test',
			'ěščřžýáíéíáýž'
		);
		$length = 10;

		$validator = new Jyxo_Input_Validator_StringLengthLessThan($length);
		$this->executeTests($validator, $good, $wrong);
		$this->assertEquals($length, $validator->getMax());

		try {
			$validator->setMax(-10);
			$this->fail('Expected exception InvalidArgumentException.');
		} catch (PHPUnit_Framework_AssertionFailedError $e) {
			throw $e;
		} catch (Exception $e) {
			// Correctly thrown exception
			$this->assertInstanceOf('InvalidArgumentException', $e);
		}
	}

	/**
	 * Tests Callback validator.
	 */
	public function testCallback()
	{
		$good = array(
			1, '1', '0.1'
		);
		$wrong = array(
			new stdClass(), false, 'OLOL'
		);

		$callbacks = array(
			'is_numeric',
			create_function('$a', 'return is_numeric($a);'),
			'SomeOtherPrefix_Some_Validator::isNumeric',
			array('SomeOtherPrefix_Some_Validator', 'isNumeric'),
			function($a) {
				return is_numeric($a);
			}
		);

		foreach ($callbacks as $callback) {
			$validator = new Jyxo_Input_Validator_Callback($callback);
			foreach ($good as $value) {
				$this->assertTrue($validator->isValid($value));
			}
			foreach ($wrong as $value) {
				$this->assertFalse($validator->isValid($value));
			}

			$this->assertSame($callback, $validator->getCallback());
			$this->assertSame(array(), $validator->getAdditionalParams());
		}

		// Test additional parameters
		$good = array(3, 9, 33);
		$wrong = array(2, 100, true, new stdClass(), 'OHAI');
		$callback = function($value, $divisor) {
			return is_numeric($value) && (0 === $value % $divisor);
		};

		$validator = new Jyxo_Input_Validator_Callback($callback, 3);
		$this->assertSame(array(3), $validator->getAdditionalParams());
		foreach ($good as $value) {
			$this->assertTrue($validator->isValid($value));
		}
		foreach ($wrong as $value) {
			$this->assertFalse($validator->isValid($value));
		}


		// Ensure that there is no such function defined
		$funcName = 'definitelyNonExistentFunction';
		if (function_exists($funcName)) {
			$this->markTestSkipped(sprintf('Function %s exists', $funcName));
		}

		// Test exception on invalid callback definition
		$invalidCallbacks = array(
			$funcName,
			true,
			1,
			new stdClass()
		);

		foreach ($invalidCallbacks as $callback) {
			try {
				$validator = new Jyxo_Input_Validator_Callback($callback);
				$this->fail('Expected exception Jyxo_Input_Validator_Exception.');
			} catch (PHPUnit_Framework_AssertionFailedError $e) {
				throw $e;
			} catch (Exception $e) {
				// Correctly thrown exception
				$this->assertInstanceOf('Jyxo_Input_Validator_Exception', $e);
			}
		}
	}

	/**
	 * Tests static call usage.
	 */
	public function testCallStatic()
	{
		static $value = 42;
		$this->assertTrue(Jyxo_Input_Validator::isInt($value));
		$this->assertTrue(Jyxo_Input_Validator::lessThan($value, $value * 2));
		$this->assertTrue(Jyxo_Input_Validator::callback(
				$value,
				function ($value, $lowerBound) {
					return is_numeric($value) && $value > $lowerBound;
				},
				41
		));

		// Tests storing in cache - the first on in cached, the second one isn't
		// because it had additional parameters that had been added to the cache ID
		$this->assertNotNull(Jyxo_Spl_ObjectCache::get('Jyxo_Input_Validator_IsInt'));
		$this->assertNull(Jyxo_Spl_ObjectCache::get('Jyxo_Input_Validator_LessThan'));
	}

	/**
	 * Tests right values.
	 *
	 * @param Jyxo_Input_ValidatorInterface $validator Validator
	 * @param array $good Right values
	 * @param array $wrong Wrong values
	 */
	private function executeTests(Jyxo_Input_ValidatorInterface $validator, array $good, array $wrong)
	{
		foreach ($good as $value) {
			$this->assertTrue(
				$validator->isValid($value),
				sprintf('Tests of value %s should be true but is false.', $value)
			);
		}

		foreach ($wrong as $value) {
			$this->assertFalse(
				$validator->isValid($value),
				sprintf('Tests of value %s should be false but is true.', $value)
			);
		}
	}

	/**
	 * Prepares right values for validation - shuffles the array
	 *
	 * @param array $good Values retrieved from the static validator getter
	 * @return array Array ready for validation
	 */
	private function getGoodValues($good)
	{
		$this->assertInternalType('array', $good, 'Variable is not an array.');
		// Adds values again - in reversed order
		$good = array_merge($good, array_reverse($good));
		$count = count($good);
		// Shuffles the array
		$this->assertTrue(shuffle($good), 'Shuffle function failures.');
		// Checks if nothing was lost during shuffle
		$this->assertEquals($count, count($good), 'Some of items lost from array during shuffle function!');

		return $good;
	}
}
