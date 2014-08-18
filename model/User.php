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
		return $res && $res->rowCount() 
			? $res->fetchAll(PDO::FETCH_ASSOC)
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

	public static function addUser($name, $password, $phone, $connectId, $email='placeholder') {
		$res = My_Model_Base::getInstance()->query(
				'INSERT INTO `user` (`name`, `password`, `phone`, `weibo_id`, `email`, `login_time`, `create_time`) VALUES (:name, :password, :phone, :weibo_id, :email, :login_time, :create_time)',
				array(
					':name' => $name, 
					':password' => self::genPassword($password), 
					':phone' => $phone, 
					':weibo_id' => $connectId, 
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

	public static function getUserOrderByScore($limit, $order = 'DESC') {
		$res = My_Model_Base::getInstance()->query(
				'SELECT * FROM `user` ORDER BY `total_score` ' . $order .' LIMIT ' . $limit,
				array()
				);
		return $res
			? $res->fetchAll(PDO::FETCH_ASSOC)
			: false;
	}

	public static function getUserOrderByPrize($limit) {
		$res = My_Model_Base::getInstance()->query(
				'SELECT * FROM `user` WHERE `prize` > 0 ORDER BY `prize` ASC LIMIT ' . intval($limit),
				array()
				);
		return $res
			? $res->fetchAll(PDO::FETCH_ASSOC)
			: false;
	}

	public static function addScore($id, $score) {
		$res = My_Model_Base::getInstance()->query(
				'UPDATE `user` SET `total_score` = `total_score` + :score WHERE `id` = :id',
				array(
					':score' => $score,
					':id' => $id,
					)
				);
		return $res ? true : false;
	}

	public static function loginUpdate($id) {
		$res = My_Model_Base::getInstance()->query(
				'UPDATE `user` SET `login_time` = :login_time WHERE `id` = :id',
				array(
					':login_time' => time(),
					':id' => $id,
					)
				);
		return $res ? true : false;
	}

	public static function getUserOrder($score) {
		$res = My_Model_Base::getInstance()->query(
				'SELECT COUNT(*) as pos FROM `user` WHERE `total_score` > :score',
				array(
					':score' => $score,
					)
				);
		if($res) {
			$res = $res->fetchAll(PDO::FETCH_ASSOC);
			return intval($res[0]['pos'])+1;
		} else {
			return 99;
		}
	}

	public static function getScoreDiff($score) {
		$res = My_Model_Base::getInstance()->query(
				'SELECT TOTAL_SCORE-:score as diff FROM `user` WHERE `total_score` > :score ORDER BY TOTAL_SCORE ASC LIMIT 1',
				array(
					':score' => $score,
					)
				);
		if($res) {
			$res = $res->fetchAll(PDO::FETCH_ASSOC);
			return intval($res[0]['diff']);
		} else {
			return 99;
		}
	}
}

