<?php
$gConfig = array();

$gConfig['db'] = array(
		'dsn' => 'mysql:host=localhost;dbname=aoxpro_zcgd;charset=utf8',
		'user' => 'root',
		'pass' => 'root',
		);

$gConfig['game'] = array(
		'total_time' => 6,
		'max_level' => 2,
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
