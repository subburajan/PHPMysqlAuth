<?php

	namespace com\smyle;

	require_once __DIR__ . "/../fwk/common/AbstractController.php";

	use com\smyle\common\AbstractController as AbstractController;

	class Controller extends AbstractController {

		public static $req;

		public function __construct() {

		}

		public function bind($slim) {
			$slim->get("/", function() use ($slim) {
				$slim->render("Home.php");
			});

			$slim->get("/about", function() use ($slim) {
				$slim->render("About.php");
			});

			$slim->get("/contact", function() use ($slim) {
				$slim->render("Contact.php");
			});

			$slim->get("/services", function() use ($slim) {
				$slim->render("Services.php");
			});

			$slim->get("/demo", function() use ($slim) {
				$slim->render("Demo.php");
			});

			$slim->get("/login", function() use ($slim) {
				if(isset($GLOBALS["_user"])) {
					$u = "/";
					header("Location:$u");
					exit;
				}
				$slim->render("account/Login.php");
			});

			$slim->get("/logout", function() use ($slim) {
				$slim->render("account/Logout.php");
			});

			$slim->get("/forgetpwd", function() use ($slim) {
				$slim->render("account/ForgetPwd.php");
			});

			$slim->get("/newuser", function() use ($slim) {
				$slim->render("account/NewUser.php");
			});
		}

		protected function getTempPath() {
			return __DIR__;
		}
	}

?>
