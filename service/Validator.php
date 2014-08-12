<?php

class My_Service_Validator {
	public static function notEmpty($value) {
		return strlen($value) == 0 ? false : true;
	}
}
