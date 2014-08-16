<?php

class My_Model_LoginPrize {
	public static function getSmall() {
		$res = My_Model_Base::getInstance()->query(
				'UPDATE `login_prize` SET `small_prize`=`small_prize` - 1 WHERE `small_prize` > 0',
				array()
				);
		return $res ? $res->rowCount() : false;
	}

	public static function getBig() {
		$res = My_Model_Base::getInstance()->query(
				'UPDATE `login_prize` SET `big_prize`=`big_prize` - 1 WHERE `big_prize` > 0',
				array()
				);
		return $res ? $res->rowCount() : false;
	}

	public static function init() {
		$res = My_Model_Base::getInstance()->query(
				'INSERT INTO `login_prize` SET `big_prize`=5,`small_prize`=15, `date_time`=' . date('Ymd') . ' ON DUPLICATE KEY UPDATE `big_prize`=5, `small_prize`=15',
				array()
				);
		return $res ? $res->rowCount() : false;
	}
}

