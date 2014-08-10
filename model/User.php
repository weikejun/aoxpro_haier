<?php

class My_Model_User {
	private static $_pwSalt = '#$@DK_(wer';

	public static function insertUpdate($weiboId, $weiboName, $timestamp) {
		$res = My_Model_Base::getInstance()->query(
				'INSERT INTO `user` (`weibo_id`, `weibo_name`, `login_time`, `create_time`) VALUES (:weibo_id, :weibo_name, :login_time, :create_time) ON DUPLICATE KEY UPDATE `weibo_name`=:weibo_name, `login_time` = :login_time',
				array(
					':weibo_id' => $weiboId, 
					':weibo_name' => $weiboName, 
					':login_time' => $timestamp, 
					':create_time' => $timestamp
				     )
				);
		return !$res || $res->rowCount() 
			? true
			: false;
	}

	public static function getByWeiboId($weiboId) {
		$res = My_Model_Base::getInstance()->query(
				'SELECT * FROM `user` WHERE `weibo_id` = :weibo_id',
				array(
					':weibo_id' => $weiboId
				     )
				);
		return !$res || $res->rowCount() 
			? $res->fetchAll(PDO::FETCH_CLASS)
			: false;
	}

	public static function getById($id) {
		$res = My_Model_Base::getInstance()->query(
				'SELECT * FROM `user` WHERE `id` = :id',
				array(
					':id' => $id,
				     )
				);
		return $res
			? $res->fetchAll(PDO::FETCH_ASSOC)
			: false;
	}

	public static function getByName($name) {
		$res = My_Model_Base::getInstance()->query(
				'SELECT * FROM `user` WHERE `name` = :name',
				array(
					':name' => $name,
				     )
				);
		return $res
			? $res->fetchAll(PDO::FETCH_ASSOC)
			: false;
	}

	public static function addUser($name, $password, $phone, $email) {
		$res = My_Model_Base::getInstance()->query(
				'INSERT INTO `user` (`name`, `password`, `phone`, `email`, `login_time`, `create_time`) VALUES (:name, :password, :phone, :email, :login_time, :create_time)',
				array(
					':name' => $name, 
					':password' => self::genPassword($password), 
					':phone' => $phone, 
					':email' => $email,
					':login_time' => time(),
					':create_time' => time(),
				     )
				);
		return $res ? true : false;
	}

	public static function passLevel($id, $score, $level) {
		$res = My_Model_Base::getInstance()->query(
				'UPDATE `user` SET `total_score` = '.$score.', `level` = '.$level.' WHERE `id` = '.$id,
				array()
				);
		return $res ? true : false;
	}

	public static function genPassword($password) {
		return MD5($password . self::$_pwSalt);
	}
}

