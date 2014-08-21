<?php

	namespace com\smyle\common;

	require_once __DIR__ . '/../../fwk/Slim/Slim.php';
	require_once __DIR__ . "/AuthenticationFilter.php";

	use com\smyle\common\AbstractController as AbstractController;
	use com\smyle\common\AuthenticationFilter as AF;

	abstract class AbstractController {

		public function init() {
			$temp_path = $this->getTempPath();

			$slim = new \Slim(array(
				'debug' => $this->debug(),
				'templates.path' => $temp_path,
				'log.level' => \Slim_Log::DEBUG
			));

			$slim->hook("slim.before", function() {
				$this->filter();
			});

			$slim->hook("slim.before.dispatch", function() {
				$this->close();
			});

			$this->bind($slim);

			try {
				$slim->run();
			} catch(Exception $e) {
				//$slim->redirect("/Error.php");
			}
		}

		abstract protected function getTempPath();

		abstract protected function bind($slim);

		protected function filter() {
			AF::hasLoggedIn();
		}

		protected function close() {
			$GLOBALS['DAO']->close();
		}

		protected function debug() {
			return isset($_COOKIE["debug"]) && $_COOKIE["debug"] == 1;
		}
	}

//-----------------------------------------------------------------------------------------
//		Global functions
//-----------------------------------------------------------------------------------------

	function getSlim() {
		return 	new \Slim(array('templates.path' => __DIR__ . "/../ui"));
	}

	function _EP($msg, $exp = "") {
		$GLOBALS["msg"] = $msg;
		$GLOBALS["exp"] = $exp;
		getSlim()->render("/Error.php");
		exit;
	}
	$GLOBALS["errPage"] = "com\smyle\common\_EP";


	function _MP($msg) {
		$GLOBALS["msg"] = $msg;
		getSlim()->render("common/Message.php");
		exit;
	}
	$GLOBALS["msgPage"] = "com\smyle\common\_MP";

	function _TMP($tmp) {
		getSlim()->render($tmp);
		exit;
	}
	$GLOBALS["msgTmp"] = "com\smyle\common\_TMP";

?>
