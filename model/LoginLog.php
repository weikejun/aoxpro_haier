<?php

class My_Model_LoginLog {
	public static function add($userId) {
		$res = My_Model_Base::getInstance()->query(
				'INSERT INTO `login_log` (`user_id`, `login_time`) VALUES (:user_id, :login_time)',
				array(
					':user_id' => $userId, 
					':login_time' => time()
				     )
				);
		return $res;
	}

	public static function getByUserId($userId) {
		$res = My_Model_Base::getInstance()->query(
				'SELECT DAYOFYEAR(FROM_UNIXTIME(`login_time`)) as dn,COUNT(*) as num FROM `login_log` WHERE `user_id`=1 GROUP BY DAYOFYEAR(FROM_UNIXTIME(`login_time`)) ASC',
				array()
				);
		return $res
			? $res->fetchAll(PDO::FETCH_ASSOC)
			: false;
	}
}

