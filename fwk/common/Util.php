<?php

	namespace com\smyle\common;

	require_once __DIR__ . "/../model/SmyleDAO.php";
	require_once __DIR__ . "/../model/CommonDO.php";
	require_once __DIR__ . "/ProcessingException.php";

	use com\smyle\model\UserDO as UserDO;
	use com\smyle\model\LogHistDO as LogHistDO;
	use com\smyle\common\ProcessingException as ProcessingException;

	define("ADMIN_EMAIL", "subburajanm@gmail.com");
	define("COOKIE_TO", 864000);
	define("RELOGIN_TO", 300);

	class Util {

		public static function getUser() {
			if(isset($GLOBALS["_user"])) {
				return $GLOBALS["_user"];
			}
		}

		public static function getUserForId($id) {
			$u = self::getUser();
			if(!empty($u) && $u->id == $id) {
				return $u;
			}
			$wc = "id=" . $id;
			$users = $GLOBALS['DAO']->get1(UserDO::$TABLE, $wc);
			if(count($users) == 0) {
				throw new \Exception("User $id Doesn't exists");
			}
			return $users[0];
		}

		public static function getLogHist($user) {
			$lh = new LogHistDO($user->id, $user->intime);
			$lh = $GLOBALS['DAO']->get($lh)[0];
			return $lh;
		}

		public static function session() {
			if(strlen(session_id()) == 0) {
				session_start();
			}
		}

		public static function setSessionVars($user) {
			$_SESSION['uid'] = $user->id;
			$_SESSION['HTTP_USER_AGENT'] = md5($_SERVER['HTTP_USER_AGENT']);
			$_SESSION['int'] = $user->intime;
		}

		public static function isExpired($user) {
			$etime = $user->intime + COOKIE_TO;
			return $etime < time();
			//return $etime > time();
		}

		public static function redirect($urlid, $user) {
			if($urlid == "wd") {
				header("Location: /desk/$user->id");
			} else {
				header("Location: /");
			}
		}

		public static function logout($user, $expired = false) {
			if($user != null) {
				$chmap = array("intime" => "");
				$wc = "id = '$user->id'";

				$dao = $GLOBALS['DAO'];
				$dao->beginTxn();
				$dao->update1(UserDO::$TABLE, $chmap, $wc);

				$lh = new LogHistDO($user->id, $user->intime);
				$outtime = time();
				if($expired) {
					$outtime = $lh->intime + COOKIE_TO;
				}

				$chmap = array("outtime" => $outtime);
				$wc = $lh->IdClause();
				$GLOBALS['DAO']->update1(LogHistDO::$TABLE, $chmap, $wc);
				$dao->closeTxn();

				unset($GLOBALS["_user"]);
				$GLOBALS["uname"] = $user->firstname . " " . $user->lastname;
			}

			unset($_SESSION['HTTP_USER_AGENT']);
			unset($_SESSION['uid']);
			unset($_SESSION['int']);

			unset($_COOKIE['uid']);
			unset($_COOKIE['ckey']);
			unset($_COOKIE['int']);
			session_destroy();
		}

		public static function loginPage($m) {
			if(empty($m->u)) {
				$m->u = $_SERVER["REQUEST_URI"] . $_SERVER["QUERY_STRING"];
			}
			$r = json_encode($m);
			header("Location:/home/login?u=$r");
			exit;
		}

		public static function success($data = "") {
			$data = array('status' => 'success', 'result' => $data);
			print json_encode($data, JSON_UNESCAPED_UNICODE);
		}

		public static function failure($arg) {
			$cz = get_class($arg);
			if($cz == "Exception") {
				$arg = array($arg->getMessage());
			} else if($cz == "com\smyle\common\ProcessingException") {
				$arg = array($arg->getMessage(), $arg->getErrs());
			}
			$data = array('status' => 'failure', 'reason' => $arg);
			print json_encode($data, JSON_UNESCAPED_UNICODE);
		}

		public static function checkCaptcha($cf, $rf) {
		    $privatekey = file_get_contents(__DIR__ . "/../../etc/captcha_pri.key");
			require_once(__DIR__ . "/../../lib/recaptchalib.php");
			$resp = recaptcha_check_answer($privatekey, $_SERVER["REMOTE_ADDR"], $cf, $rf);
			if(!$resp->is_valid) {
				throw new \Exception("Captcha Doesn't match");
			}
		}
	}

	class LoginMessage {
		public $u;
		public $m;
		public $e = false;
		public $r;

		public function __construct($url, $msg, $ise = false, $rl = "") {
			$this->u = $url;
			$this->m = $msg;
			$this->e = $ise;
			$this->r = $rl;
		}
	}

?>
