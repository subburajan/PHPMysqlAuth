<?php

	namespace com\smyle\model;

	abstract class AbstractDO {
		public function fromJson($json) {
			$this->fromJson1(json_decode($json));
		}

		public function fromJson1($data) {
			foreach($data as $k => $v) {
				if(property_exists($this, $k)) {
					$this->{$k} = $v;
				}
			}
		}

		public function toJson() {
			return json_encode($this);
		}

		public function insertSQL() {
			$c = "";
			$p = "";
			foreach($this as $k => $v) {
				$c .= "$k,";
				$p .= ":$k,";
			}
			$c = rtrim($c, ",");
			$p = rtrim($p, ",");
			$sql = "INSERT INTO " . $this::$TABLE . "(" . $c . ") VALUES (" . $p . ")";
			return $sql;
		}

		public static $AI = 0;
	}

	class ActivationDO extends AbstractDO {
		public $id;
		public $email;
		public $toname;

		public $action;
		public $params;
		public $secid;
		public $activecode = 0;
		public $active = "N";

		public function __construct($id) {
			$this->id = $id;
		}

		public static $TABLE = "Activation";

		public function IdClause() {
			return "id = $this->id";
		}

		public static $AI = "id";

		public static $CFG = array("email" => array("email", "M"), "toname" => array("text", "M"));
	}

	class UserDO extends AbstractDO {
		public $id;
		public $email;
		public $pwd;
		public $lastname;
		public $firstname;
		public $level = USERS;
		public $banned = 'N';
		public $intime = '0';

		public function __construct($id) {
			$this->id = $id;
		}

		public static $TABLE = "User";

		public function IdClause() {
			return "id = $this->id";
		}

		public static $AI = "id";
	}

	class UserCommonDO extends AbstractDO {
		public $userid;

		public function parentIdClause() {
			return "userid = " .$this->userid;
		}
	}

	class LogHistDO extends UserCommonDO {
		public $ckey;
		public $intime;
		public $outtime = "";
		public $description = "";

		public function __construct($userid, $intime) {
			$this->userid = $userid;
			$this->intime = $intime;
		}

		public static $TABLE = "LogHist";

		public function IdClause() {
			return "userid = '$this->userid' and intime = '$this->intime'";
		}
	}

	class ContactDO extends AbstractDO {
		public $id;
		public $email;
		public $name;
		public $subject;
		public $message;

		public static $TABLE = "Contact";

		public function IdClause() {
			return "id = $this->id";
		}

		public static $AI = "id";

		public static $CFG = array("email" => array("email", "M"), "name" => array("text", "M"),
			"subject" => array("text", "M"), "message" => array("text_limit", "M", 250));
	}

?>
