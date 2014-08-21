<?php

	namespace com\smyle\services\common;
	
	use com\smyle\model\GroupDO as GroupDO;
	
	class DeskUtil {
		
		public static function getGroups($userid) {
			$group = new GroupDO($userid, -1);
			$data = $GLOBALS['DAO']->getSiblings($group);
			$res = array();
			foreach($data as $d) {
				$res[$d->id] = $d->name; 
			}
			return $res;		
		}
	
	}

?>
