<?php

	namespace com\smyle\common;

	require_once __DIR__ . "/Util.php";

	use com\smyle\common\Util as Util;
	use com\smyle\common\LoginMessage as LoginMessage;

	define("USERS", 0);
	define("EMPLOYEE", 1);
	define("TESTER", 2);
	define("ADMIN_NEWS", 3);
	define("ADMIN_ESHOP", 4);
	define("ADMIN", 5);
	define("MANAGER", 6);

	class AuthenticationFilter {

		public static function checkLogin() {
			try {
				self::checkLogin1();
			} catch(\Exception $e) {
				$e = new LoginMessage("", $e->getMessage(), true);
				Util::loginPage($e);
			}
		}

		public static function checkLogin1() {
			Util::session();
			if(isset($_SESSION['HTTP_USER_AGENT'])) {
				$ua_server = md5($_SERVER['HTTP_USER_AGENT']);
				if($ua_server != $_SESSION['HTTP_USER_AGENT']) {
					Util::logout(null, false);
					throw new \Exception("Need Login");
				}
			}
			if(isset($_SESSION['uid'])) {
				$user = Util::getUserForId($_SESSION['uid']);
				if(!Util::isExpired($user)) {
					if($user->intime != $_SESSION['int']) {
						self::_throw1($user);
					}
					$GLOBALS["_user"] = $user;
					return;
				}
				self::_throw2($user);
			} else if(isset($_COOKIE['uid']) && isset($_COOKIE['int']) && isset($_COOKIE['ckey'])) {
				$user = Util::getUserForId($_COOKIE['uid']);
				if($user->intime != $_COOKIE['int']) {
					self::_throw1($user);
				}
				$lh = Util::getLogHist($user);
				if($lh->ckey == $_COOKIE['ckey']) {
					$GLOBALS["_user"] = $user;
					Util::setSessionVars($user);
					return;
				}
				self::_throw2($user);
			}
			throw new \Exception("Please Login");
		}

		public static function hasLoggedIn() {
			try {
				self::hasLoggedIn1();
			} catch(\Exception $e) {
				//suppressing the error
			}
		}

		private static function hasLoggedIn1() {
			Util::session();
			if(isset($_SESSION['HTTP_USER_AGENT'])) {
				$ua_server = md5($_SERVER['HTTP_USER_AGENT']);
				if($ua_server != $_SESSION['HTTP_USER_AGENT']) {
					return;
				}
			}
			if(isset($_SESSION['uid'])) {
				$user = Util::getUserForId($_SESSION['uid']);
				if($user->intime == $_SESSION['int'] && !Util::isExpired($user)) {
					$GLOBALS["_user"] = $user;
				}
			} else if(isset($_COOKIE['uid']) && isset($_COOKIE['int']) && isset($_COOKIE['ckey'])) {
				$user = Util::getUserForId($_COOKIE['uid']);
				if($user->intime != $_COOKIE['int']) {
					return;
				}
				$lh = Util::getLogHist($user);
				if($lh->ckey == $_COOKIE['ckey']) {
					Util::setSessionVars($user);
					$GLOBALS["_user"] = $user;
				}
			}
		}

		public static function checkULevel($level) {
			$u = Util::getUser();
			if(empty($u)) {
				Util::loginPage(new LoginMessage("", "Please Login", true));
			}
			if($u->level < $level) {
				$GLOBALS["errPage"]("",	new \Exception(
					"Permission denied for user " . $u->firstname . " " . $u->lastname));
				exit;
			}
		}

		private static function _throw2($user) {
			Util::logout($user, true);
			throw new \Exception("$user->id Login expired. Please Login");
		}

		private static function _throw1($user) {
			Util::logout(null, false);
			throw new \Exception("$user->id: You have Logged in different browser, So Please Login Again");
		}
	}

?>
