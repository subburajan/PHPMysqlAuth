<?php
	
	namespace com\smyle\model;
	
	require_once __DIR__ . "/CommonDO.php";
	
	use com\smyle\model\UserCommonDO as UserCommonDO;
	use com\smyle\model\AbstractDO as AbstractDO;
	
	class GroupDO extends UserCommonDO {
		public $name;
		public $id;
		
		public function __construct($userid, $id) {
			$this->userid = $userid;
			$this->id = $id;
		}
		
		public function IdClause() {
			return "id = '$this->id'";
		}
		
		public static $TABLE = "DeskGroup";
	}
	
	class ContentDO extends AbstractDO {
		public $title;
		public $time;
		public $groupid;
		
		public static $TABLE = "DeskContent";

		public function __construct($groupid) {
			$this->groupid = $groupid;
		}
		
		public function parentIdClause() {
			return "groupid = $this->groupid";
		}
	}
?>
