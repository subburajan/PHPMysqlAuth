<?php
	
	namespace com\smyle\common;

	class AC {
		
		public static function generate() {
			return rand(1000, 9999);
		}
		
		public static function merge($id, $ac) {
			return $id . $ac;
		}
		
		public static function split($ac) {
			$i = strlen($ac);
			if($i < 5) {
				throw new \Exception("Invalid Activation URL, Please use the right URL which has been sent to you");
			}
			$i -= 4;
			return array(substr($ac, 0, $i), substr($ac, $i));
		}	
	
	}


?>
