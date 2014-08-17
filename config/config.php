<?php
$gConfig = array();

$gConfig['db'] = array(
		'dsn' => 'mysql:host=localhost;dbname=aoxpro_haier;charset=utf8',
		'user' => 'root',
		'pass' => 'root',
		);

$gConfig['game'] = array(
		'lv_conf' => array(
			0 => array(
				'time' => 30,
				'monster' => 30,
				'm_score' => 100,
				'boss' => 0,
				'b_score' => 0,
				'm_attack' => 7,
				'b_attack' => 7,
				'm_delay' => 2,
				'move_spe' => 3,
				),
			1 => array(
				'time' => 40,
				'monster' => 40,
				'm_score' => 100,
				'boss' => 0,
				'b_score' => 0,
				'm_attack' => 6,
				'b_attack' => 6,
				'm_delay' => 2,
				'move_spe' => 3,
				),
			2 => array(
				'time' => 50,
				'monster' => 50,
				'm_score' => 100,
				'boss' => 0,
				'b_score' => 0,
				'm_attack' => 5,
				'b_attack' => 5,
				'm_delay' => 2,
				'move_spe' => 3,
				),
			3 => array(
				'time' => 60,
				'monster' => 60,
				'm_score' => 100,
				'boss' => 0,
				'b_score' => 0,
				'm_attack' => 4,
				'b_attack' => 4,
				'm_delay' => 2,
				'move_spe' => 3,
				),
			4 => array(
				'time' => 70,
				'monster' => 70,
				'm_score' => 100,
				'boss' => 0,
				'b_score' => 0,
				'm_attack' => 3,
				'b_attack' => 3,
				'm_delay' => 2,
				'move_spe' => 3,
				),
			5 => array(
				'time' => 90,
				'monster' => 90,
				'm_score' => 100,
				'boss' => 1,
				'b_score' => 10000,
				'm_attack' => 2,
				'b_attack' => 2,
				'm_delay' => 2,
				'move_spe' => 3,
				),
			),
		);

$gConfig['share'] = array(
		'pic_url' => 'http://zcgd2014.aoxpro.com/img/share_game.jpg',
		'content' => array(
			1 =>'我参加了中钞国鼎十年成长•文化黄金——#马年抢金大作战#活动。十年，中钞国鼎打造高品质产品；十年，中钞国鼎传承经典，讲述黄金背后的故事。活动每天奖品不断，等你拿！@中钞国鼎官博',
			2 => '我通关了中钞国鼎十年成长•文化黄金——#马年抢金大作战#游戏，并正确回答了#抢金一问#！仿佛已经看到了抢金大奖在向我招手！活动每天奖品不断，你也快来参加吧！@中钞国鼎官博'
			),
		'content_error' => '我通关了中钞国鼎十年成长•文化黄金——#马年抢金大作战#游戏，并正确回答了#抢金一问#！仿佛已经看到了抢金大奖在向我招手！活动每天奖品不断，你也快来参加吧！@中钞国鼎官博',
		'join_tips' => ''
		);
