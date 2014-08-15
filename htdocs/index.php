<?php
ini_set('session.gc_maxlifetime', 2592000*3);
ini_set('session.cookie_lifetime', 2592000*3);
require_once(dirname(dirname(__FILE__)) . '/ConfigLoader.php');

$controller = new My_Action_Game();
$controller->process();
