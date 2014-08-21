<?php
	
	namespace com\smyle\model;
	
	require_once __DIR__ . "/CommonDO.php";
	
	use com\smyle\model\UserCommonDO as UserCommonDO;
	use com\smyle\model\AbstractDO as AbstractDO;
	
	class ProfileDO extends UserCommonDO {
		public $emailnot = "N";		
		public $address = "";
		public $country = -1;
		public $secques = -1;
		public $secans = '';
		
		public function __construct($userid) {
			$this->userid = $userid;
		}
		
		public static $TABLE = "Profile";
	}
	
	class DownloaderDO extends UserCommonDO {
		public $jobtitle;
		public $company;
		public $purpose;
		
		public function __construct($userid) {
			$this->userid = $userid;
		}
		
		public static $TABLE = "Downloader";
	}
?>
