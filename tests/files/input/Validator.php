<?php

/**
 * Testing validator with a prefix.
 */
class SomeOtherPrefix_Some_Validator {

	public static function isNumeric($value) {
		return is_numeric($value);
	}

}
