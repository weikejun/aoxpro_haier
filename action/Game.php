<?php

class My_Action_Game extends My_Action_Abstract {
	private $_weiboService = null;

	private $_exception = null;

	private $_weiboUser = null;

	private $_platformKey = array('sina', 'tencent');

	protected $_isAuth = false;

	private function _doLogin($user) {
		$_SESSION['auth']['user'] = $user;
	}

	public function loginAction() {
		$retData = array(
				'code' => 1,
				'msg' => '登录成功',
				);
		try {
			if(strtoupper($this->getServer('REQUEST_METHOD')) == 'POST') {
				if(My_Service_Validator::notEmpty(trim($this->getRequest('name'))) === false) {
					throw new Exception('用户名不能为空');
				}
				if(My_Service_Validator::notEmpty($this->getRequest('password')) === false) {
					throw new Exception('密码不能为空');
				}
				$user = My_Model_User::getByName(trim($this->getRequest('name')));
				if($user === false) {
					throw new Exception('服务器出错，请稍等再试');
				} elseif(empty($user)) {
					throw new Exception('用户名不存在');
				}
				if(My_Model_User::genPassword($this->getRequest('password')) !== $user[0]['password']) {
					throw new Exception('密码不正确');
				}
				$this->_doLogin($user);
			} else {
				throw new Exception('用户请求出错');
			}
		} catch(Exception $e) {
			$retData['code'] = 0;
			$retData['msg'] = $e->getMessage();
		}
		$this->setViewParams('data', $retData);
//		$ret = null;
//		try {
//			$ret = My_Model_User::insertUpdate(
//					$this->_weiboUser['id'], 
//					$this->_weiboUser['name'],
//					$this->getActionTime()
//					);
//			if (!$ret) {
//				throw new Exception('update user error');
//			}
//			My_Model_UserStatus::deleteByWeiboId($this->_weiboUser['id']);
//		} catch (Exception $e) {
//			$this->_exception = $e;
//		}
//
//		$this->setViewParams(
//				'data', 
//				array('success' => !empty($ret) ? 1 : 0)
//				);
	}

	public function regAction() {
		$retData = array(
				'code' => 1,
				'msg' => '注册成功',
				);
		try {
			if(strtoupper($this->getServer('REQUEST_METHOD')) == 'POST') {
				if(My_Service_Validator::notEmpty(trim($this->getRequest('name'))) === false) {
					throw new Exception('用户名不能为空');
				}
				$user = My_Model_User::getByName(trim($this->getrequest('name')));
				if($user === false) {
					throw new Exception('服务器出错，请稍等再试');
				} elseif(!empty($user)) {
					throw new Exception('用户名已存在，请您更换用户名');
				}
				if(My_Service_Validator::notEmpty($this->getRequest('password')) === false) {
					throw new Exception('密码不能为空');
				}
				if(My_Service_Validator::notEmpty(trim($this->getRequest('phone'))) === false) {
					throw new Exception('手机号不能为空');
				}
				if(My_Service_Validator::notEmpty(trim($this->getRequest('email'))) === false) {
					throw new Exception('email不能为空');
				}
				$ret = My_Model_User::addUser(
						$this->getRequest('name'),
						$this->getRequest('password'),
						$this->getRequest('phone'),
						$this->getRequest('email')
						);
				if(!$ret) {
					throw new Exception('服务器出错，请稍等再试');
				} 
				$user = My_Model_User::getByName(trim($this->getrequest('name')));
				if(empty($user)) {
					throw new Exception('服务器出错，请稍等再试');
				}
				$this->_doLogin($user);
			} else {
				throw new Exception('用户请求出错');
			}
		} catch(Exception $e) {
			$retData['code'] = 0;
			$retData['msg'] = $e->getMessage();
		}
		$this->setViewParams('data', $retData);
	}

	public function logoutAction() {
		$this->setSession(array());
		session_unset();
		session_destroy();
	}

	public function infoAction() {
		$retData = array(
				'code' => 1,
				'msg' => '返回成功',
				);
		$retData['name'] = $_SESSION['auth']['user'][0]['name'];
		$retData['total_score'] = $_SESSION['auth']['user'][0]['total_score'];
		$retData['level'] = $_SESSION['auth']['user'][0]['level'];

		$this->setViewParams('data', $retData);
	}

	public function playAction() {
		$retData = array(
				'code' => 1,
				'msg' => '游戏开始',
				);
		try {
			if(true || strtoupper($this->getServer('REQUEST_METHOD')) == 'POST') {
				$level = $this->getRequest('level');
				$user = $this->getSession('auth')['user'];
				if(empty($level) && intval($level) !== 0) {
					$level = $user[0]['level'];
				}
				if($level > $user[0]['level']) {
					throw new Exception('用户关卡选择出错');
				}
				$_SESSION['play']['start_time'] = time();
				$_SESSION['play']['status'] = 1;
				$_SESSION['play']['level'] = $level;
				$gameConfig = ConfigLoader::getInstance()->get('game');
				$retData['lv_conf'] = $gameConfig['lv_conf'][$level];
				$retData['total_score'] = $user[0]['total_score'];
				$retData['level'] = $level;
			} else {
				throw new Exception('用户请求出错');
			}
		} catch (Exception $e) {
			$retData['code'] = 0;
			$retData['msg'] = $e->getMessage();

		}

		$this->setViewParams('data', $retData);
	}

	public function passAction() {
		$retData = array(
				'code' => 1,
				'msg' => '游戏完成',
				);
		try {
			if(true || strtoupper($this->getServer('REQUEST_METHOD')) == 'POST') {
				if(!isset($_SESSION['play']) || $_SESSION['play']['status'] != 1) {
					throw new Exception('用户不在游戏中');
				}
				$gameConfig = ConfigLoader::getInstance()->get('game');
				$lvConf = $gameConfig['lv_conf'][$_SESSION['play']['level']];
				if(time() - $_SESSION['play']['start_time'] < $lvConf['time']) {
					throw new Exception('用户正在游戏，时间未到');
				}
				$score = intval($this->getRequest('score'));
				if($score > $lvConf['m_score'] * $lvConf['monster']
						+ $lvConf['b_score'] * $lvConf['boss']) {
					throw new Exception('用户分数异常');
				}
				$pssLv = $_SESSION['auth']['user'][0]['level'];
				$curLv = $_SESSION['play']['level'];
				if($curLv < count($gameConfig['lv_conf']) - 1) {
					$nxtLv = $curLv + 1;
				} else {
					$nxtLv = count($gameConfig['lv_conf']) - 1;
				}
				if($nxtLv > $pssLv) {
					$pssLv = $nxtLv;
				}

				$ret = My_Model_User::passLevel(
						$_SESSION['auth']['user'][0]['id'],
						$score = intval($score)+intval($_SESSION['auth']['user'][0]['total_score']), 
						$pssLv);
				if(!$ret) {
					throw new Exception('服务器出错，请稍等再试');
				}
				$_SESSION['play']['status'] = 0;
				$_SESSION['auth']['user'][0]['total_score'] = $score;
				$_SESSION['auth']['user'][0]['level'] = $pssLv;

				$retData['total_score'] = $score;
				$retData['level_next'] = $nxtLv;
				$retData['level_max'] = count($gameConfig['lv_conf']) - 1;
			} else {
				throw new Exception('用户请求出错');
			}
		} catch (Exception $e) {
			$retData['code'] = 0;
			$retData['msg'] = $e->getMessage();
		}

		$this->setViewParams('data', $retData);
	}

	public function shareAction() {
		$session = $this->getSession();
		$content = urldecode($this->getRequest('content'));
		if (empty($content)) {
			$content = ConfigLoader::getInstance()->get('share', 'content_error') . ' http://' . $this->getServer('HTTP_HOST');
		}
		$picUrl = urldecode($this->getRequest('pic_url'));
		if (empty($picUrl)) {
			$picUrl = ConfigLoader::getInstance()->get('share', 'pic_url');
		}
		$follow = $this->getRequest('follow');
		if ($session['platform'] == 'sina') {
			$ret = $this->_weiboService->upload($content, $picUrl);
			if($ret) {
				My_Model_UserFeeds::insert(
						$this->_weiboUser['id'],
						$this->_weiboUser['name'],
						$this->_weiboUser['head'],
						$ret['text'],
						$ret['thumbnail_pic'],
						$session['platform']
						);
				if ($follow) {
					$this->_weiboService->follow_by_name('中钞国鼎官博');
				}
			}
			
		} else {
			$ret = Tencent::api('t/upload_pic', array(
						'format' => 'json',
						'pic_url' => $picUrl,
						'pic_type' => 1,
						), 'post');
			$ret = json_decode($ret, true);
			if ($ret['errcode'] == 0) {
				$picUrl = $ret['data']['imgurl'];
			}
			$ret = Tencent::api('t/add_pic_url', array(
						'format' => 'json',
						'content' => $content,
						'clientip' => My_Service_Game::getIP(),
						'pic_url' => ($ret['errcode'] == 0 ? $ret['data']['imgurl'] : $picUrl),
					), 'post');
			$ret = json_decode($ret, true);
			if ($ret['errcode'] == 0) {
				/*$ret = Tencent::api('t/show', array(
							'format' => 'json',
							'id' => $ret['data']['id']
							)
						);*/
				My_Model_UserFeeds::insert(
						"t_" . $this->_weiboUser['passport'],
						$this->_weiboUser['name'],
						empty($this->_weiboUser['head']) ? 'http://mat1.gtimg.com/www/mb/img/p1/head_normal_50.png' : $this->_weiboUser['head'] . '/50',
						$content,
						strpos($picUrl, 'qpic.cn') !== false ? "$picUrl/120" : $picUrl,
						$session['platform']
						);
				if ($follow) {
					$ret = Tencent::api('friends/add', array(
								'format' => 'json',
								'name' => urlencode('cfca1977964945')
								), 'post');
				}
			}
		}
		if($this->getRequest('bingo') == '888') {
			$ret = My_Model_QualifiedUser::insertUpdate(
					$this->_weiboUser['id'],
					$this->_weiboUser['name'],
					$this->_weiboUser['passport']
					);
		}
		$this->setViewParams('data', 
				array(
					'success' => 1,
					'content' => $content,
				     )
				);
	}

	public function getShareAction() {
		$contentId = $this->getRequest('contentId');
		$contents = ConfigLoader::getInstance()->get('share', 'content');
		$content = ConfigLoader::getInstance()->get('share', 'content_error');
		if (isset($contents[$contentId])) {
			$content = $contents[$contentId] . ' http://' . $this->getServer('HTTP_HOST');
		}
		$this->setViewParams('data', 
				array(
					'success' => 1,
					'content' => $content,
				     )
				);
	}

	public function indexAction() {
	}

	public function callbackAction() {
		$defUrl = "http://" . $this->getServer('HTTP_HOST');
		$session = $this->getSession();
		$platform = $session['platform'];
		$ru = $session['ru'];
		if (empty($ru)) {
			$ru = $defUrl;
		}

		if ($platform == 'sina') { // sina authorize
			$o = new SaeTOAuthV2(WB_AKEY , WB_SKEY);
			$code = $this->getRequest('code');
			$token = null;
			if (!empty($o) && !empty($code)) {
				try {
					$token = $o->getAccessToken('code', array(
								'code' => $code,
								'redirect_uri' => $ru
								)) ;
				} catch (OAuthException $e) {}
			}


			if (isset($token['access_token'])) { // 授权成功
				foreach($token as $key => $value) {
					$session["s_$key"] = $value;
				}
				$this->setSession($session);
				setcookie('weibojs_' . $o->client_id, http_build_query($token));
				$this->redirect($ru);
			} 
		} else { // tencent authorize
			OAuth::init(TX_AKEY, TX_SKEY);
			Tencent::$debug = TX_DEBUG;
			$code = $this->getRequest('code');
			$openid = $this->getRequest('openid');
			$openkey = $this->getRequest('openkey');
			//获取授权token
			$host = $this->getServer('HTTP_HOST');
			$url = OAuth::getAccessToken($code, "http://$host/index.php?action=callback");
			$r = Http::request($url);
			parse_str($r, $out);
			//存储授权数据
			if ($out['access_token']) {
				$session = array_merge($session, array(
						't_access_token' => $out['access_token'],
						't_refresh_token' => $out['refresh_token'],
						't_expire_in' => $out['expires_in'],
						't_code' => $code,
						't_openid' => $openid,
						't_openkey' => $openkey
						));
				$this->setSession($session);

				//验证授权
				$r = OAuth::checkOAuthValid();
				$this->redirect($ru);
			} 
		}

		$this->redirect($defUrl);
	}

	public function gameAction() {
	}

	public function pvAction() {
		$ret = null;
		$pv = 0;
		try {
			My_Model_PvStat::incr();
			$ret = My_Model_PvStat::get();
			$pv = $ret ? $ret[0]->count : $pv;
		} catch (Exception $e) {
			$this->_exception = $e;
		}

		$this->setViewParams(
				'data', 
				array('pv' => $pv)
				);
	}

	public function authAction() {}

	public function unauthAction() {}

	public function connectAction() {
		// set platform
		$p = $this->getRequest('platform');
		if (!in_array($p, $this->_platformKey)) {
			$p = $this->_platformKey[0];
		}
		$session = $this->getSession();
		$session['platform'] = $p;
		$this->setSession($session);

		// set callback url
		$host = $this->getServer('HTTP_HOST');
		$ru = $session['ru'];
		$callbackUrl = "http://$host/index.php?action=callback";

		// authorize
		if ($p == 'sina') { // sina authorize
			$o = new SaeTOAuthV2(WB_AKEY, WB_SKEY);
			$url = $o->getAuthorizeURL($callbackUrl);
		} else { // tencent authorize
			OAuth::init(TX_AKEY, TX_SKEY);
			Tencent::$debug = TX_DEBUG;
			$url = OAuth::getAuthorizeURL($callbackUrl);
		}

		// redirect
		$this->redirect($url);
	}

	public function statAction() {
		$this->setViewParams('data', 
				array(
					'bonus' => My_Model_BonusUser::getBonusList(),
					'rank' => My_Model_User::getOrderList(),
				     )
				);
	}

	public function topicAction() {
		$count = intval($this->getRequest('count'));
		$page = intval($this->getRequest('page'));
		$platform = $this->getRequest('platform');

		$offset = ($page - 1) * $count;
		$limit = $count;
		
		$res = My_Model_UserFeeds::get($platform, $offset, $limit);
		$total = My_Model_UserFeeds::total($platform);

		$this->setViewParams('data', 
				array(
					'count' => $count,
					'page' => $page,
					'total_number' => $total[0]['total'],
					'statuses' => $res,
				     )
				);
		
	}

	protected function _postAction() {
		$sParams = $this->getSession();
		$actionBody = sprintf(
				'ip=%s|sid=%s|msg=%s|uri=%s',
				My_Service_Game::getIP(),
				session_id(),
				is_null($this->_exception) ? 'done' : $this->_exception->getMessage(),
				$this->getServer('REQUEST_URI')
				);
		My_Model_ActionLog::logAction(
				empty($sParams['s_uid']) ? 0 : $sParams['s_uid'],
				$this->getActionName(),
				$actionBody,
				$this->getActionTime()
				);
	}

	protected function _preAction() {
		$unauthActions = array('login', 'reg', 'callback', 'connect', 'auth', 'unauth', 'pv', 'topic');
		if(!in_array($this->getActionName(), $unauthActions)) {
			$session = $this->getSession('auth');
			if(!isset($session['user']) || empty($session['user'][0]['id'])) {
				$this->_actionName = 'unauth';
			}
		}
//		if(!in_array($this->getActionName(), $unauthActions)) {
//			$this->_verifyAuth();
//			if (!$this->_isAuth) {
//				$this->_actionName = 'unauth';
//			}
//		}
	}

	private function _verifyAuth() {
		$session = $this->getSession();
		$host = $this->getServer('HTTP_HOST');
		$ru = urldecode($this->getRequest('ru'));
		if (empty($ru)) {
			$ru = "http://$host" . $this->getServer('REQUEST_URI');
		}
		if (!isset($session['platform']) 
				|| ($this->getRequest('platform') && $session['platform'] != $this->getRequest('platform'))) {
			$session['ru'] = $ru;
			$this->setSession($session);
			$this->redirect("http://$host/index.php?action=auth");
		}

		if ($session['platform'] == 'sina') {
			$this->_weiboService = new SaeTClientV2( 
					WB_AKEY, 
					WB_SKEY,
					$session['s_access_token']
					);
			$weiboUser = !empty($this->_weiboService) 
				? $this->_weiboService->show_user_by_id($session['s_uid'])
				: null;
			if (empty($weiboUser) || !empty($weiboUser['error'])) {
				return $this->_isAuth = false;
			}

			$this->_weiboUser = array(
					'id' => 's_' . $weiboUser['id'],
					'name' => $weiboUser['screen_name'],
					'passport' => $weiboUser['name'],
					'head' => $weiboUser['profile_image_url']
					);
		} else {
			OAuth::init(TX_AKEY, TX_SKEY);
			Tencent::$debug = TX_DEBUG;
			$weiboUser = Tencent::api('user/info');
			$weiboUser = json_decode($weiboUser, true);
			if (empty($weiboUser) || !empty($weiboUser['errcode'])) {
				return $this->_isAuth = false;
			}
			$this->_weiboUser = array(
					'id' => 't_' . $weiboUser['data']['openid'],
					'name' => $weiboUser['data']['nick'],
					'passport' => $weiboUser['data']['name'],
					'head' => $weiboUser['data']['head']
					);
		}

		return $this->_isAuth = true;
	}

}
