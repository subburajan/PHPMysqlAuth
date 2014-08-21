<?php

	namespace com\smyle\common;

	class ProcessingException extends \Exception {
		private $errs = array();

		public function __construct() {
			$args = func_get_args();
			switch(count($args)) {
				case 2:
					parent::__construct($args[0]);
					$this->errs = $args[1];
					break;
				case 1:
					$this->errs = $args[0];
				default:
					parent::__construct("Server Internal Error");
			}
		}

		public function getErrs() {
			return $this->errs;
		}
	}
?>
