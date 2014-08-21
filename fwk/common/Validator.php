<?php
	namespace com\smyle\common;

	require_once __DIR__ . "/ProcessingException.php";
	//require_once __DIR__ . "/../model/CommonDO.php";

	//use com\smyle\model\ContactDO as ContactDO;
	use com\smyle\common\ProcessingException as ProcessingException;

	class Validator {
		public static function validate($obj, $o_cfg = array(), $o_val = array()) {
			if(!property_exists($obj, "CFG")) {
				throw new \Exception("Validation Configuration is Missing");
			}
			$cz = get_class($obj);
			$cfg = $cz::$CFG;
			$errs = array();
			foreach($cfg as $k => $v) {
				self::_validate($k, $obj->{$k}, $v, $errs);
			}
			foreach($o_cfg as $k => $v) {
				self::_validate($k, $o_val[$k], $v, $errs);
			}
			if(count($errs) > 0) {
				throw new ProcessingException("Please refill the incorrect inputs", $errs);
			}
		}

		public static function validate1($o_cfg, $o_val) {
			$errs = array();
			foreach($o_cfg as $k => $v) {
				self::_validate($k, $o_val[$k], $v, $errs);
			}
			if(count($errs) > 0) {
				throw new ProcessingException("Please refill the incorrect inputs", $errs);
			}
		}

		public static function _validate($k, $val, $v, &$errs) {
			$val = trim($val);
			if($v[1] == "M" && strlen($val) == 0) {
				$errs[$k] = "Should not be empty";
			} else {
				$m = $v[0];
				try {
					self::$m($val, $v);
				} catch(\Exception $e) {
					$errs[$k] = $e->getMessage();
				}
			}
		}

		public static function email($value, &$opts) {
			if(!filter_var($value, FILTER_VALIDATE_EMAIL)) {
				throw new \Exception("example: abc@xyz.com");
			}
		}

		public static function text($value, $opts) {

		}

		public static function number($value, $opts) {
			if(!filter_var($value, FILTER_VALIDATE_INT)) {
				throw new \Exception("Should be a number");
			}
		}

		public static function country($value, $opts) {
			if($value != 0 && !filter_var($value, FILTER_VALIDATE_INT,
					array('options' => array('min_range' => 0, 'max_range' => 234)))) {
				throw new \Exception("Please Choose one");
			}
		}

		public static function secques($value, $opts) {
			if(!filter_var($value, FILTER_VALIDATE_INT,
					array('options' => array('min_range' => 1, 'max_range' => 5)))) {
				throw new \Exception("Please Choose one");
			}
		}

		public static function pwd($value, $opts) {
			if(strlen($value) < 8) {
				throw new \Exception("Password should be more than 8 characters");
			}
		}

		public static function text_limit($value, $opts) {
			if(strlen($value) > $opts[2]) {
				throw new \Exception("Maximum length is " . $opts[2]);
			}
		}
	}

	//test();

?>
