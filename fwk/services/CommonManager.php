<?php

	namespace com\smyle\services\common;

	require_once __DIR__ . "/../common/Validator.php";

	define("SALT_LENGTH", 10);
	define("KEY_LENGTH", 10);

	use com\smyle\model\UserDO as UserDO;
	use com\smyle\model\ProfileDO as ProfileDO;

	class CommonManager {

		public static function getSecpwd($pwd) {
			$salt = substr(md5(uniqid(rand(), true)), 0, SALT_LENGTH);
			return $salt . sha1($pwd . $salt);
		}

		public static function chkPwd($pwd_db, $pwd) {
			$salt = substr($pwd_db, 0, SALT_LENGTH);
			$pwd_db = substr($pwd_db, SALT_LENGTH);
			$pwd = sha1($pwd . $salt);
			return ($pwd_db == $pwd);
		}

		public static function getUserForEmail($email) {
			$wc = "email='" . $email . "'";
			$u = $GLOBALS['DAO']->get1(UserDO::$TABLE, $wc);
			if(empty($u)) {
				throw new \Exception("User doesn't exists for Email $email");
			}
			return $u[0];
		}

		public static function getSQ($id) {
			$sel = array("secques", "secans");
			$wc = " userid = '$id'";
			$res = $GLOBALS['DAO']->get2(ProfileDO::$TABLE, $sel, $wc);
			return $res[0];
		}

		/**
		 * API call
		 */
		public static function changeSQ($req) {
			$chmap = array("secques" => $req->secques, "secans" => $req->secans);
			$wc = " userid = '$req->id'";
			$res = $GLOBALS['DAO']->update1(ProfileDO::$TABLE, $chmap, $wc);
			return $res;
		}

	}


?>
