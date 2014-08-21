<?php

	namespace com\smyle\model;

	require_once __DIR__ . "/db/DAO.php";

	use com\smyle\model\db\GenericDAO as GenericDAO;

	class SmyleDAO extends GenericDAO {

		public function __construct() {
			parent::__construct();
		}

		public function insert($do_obj) {
			$sql = $do_obj->insertSQL();
			$cz = get_class($do_obj);
			$ai = $cz::$AI;
			$res = parent::_insert($sql, $do_obj, $ai);
			if(!$res) {
				throw new \Exception("Server Internal Error");
			}
		}

		public function update($do_obj, $chmap, $wc = null) {
			$clazz = get_class($do_obj);
			return self::update1($clazz::$TABLE, $chmap, $wc);
		}

		public function update1($table, $chmap, $wc = null) {
			$sql = "UPDATE " . $table . " SET";
			foreach($chmap as $k => $v) {
				$sql .= " $k=:$k,";
			}
			$sql = rtrim($sql, ",");
			if($wc != null) {
				$sql .= " WHERE " . $wc;
			}

			return parent::_update($sql, $chmap);
		}

		public function deleteObj($do_obj) {
			$clazz = get_class($do_obj);
			$wc = $do_obj->IdClause();
			return parent::_delete($clazz::$TABLE, $wc);
		}

//--------------------------------------------------------------------------------------
//			Transaction
//--------------------------------------------------------------------------------------

		public function beginTxn() {
			self::getCon();
			$this->con->beginTransaction();
		}

		public function closeTxn() {
			$this->con->commit();
		}

		protected function handleException($e) {
			if($this->con->inTransaction()) {
				$this->con->rollback();
			}
			return parent::handleException($e);
		}

//--------------------------------------------------------------------------------------
//			Queries
//--------------------------------------------------------------------------------------

		public function getAll($TABLE) {
			$sql = "SELECT * FROM " . $TABLE;
			return parent::query($sql);
		}

		public function get($do_obj) {
			$clazz = get_class($do_obj);
			return self::get1($clazz::$TABLE, $do_obj->IdClause());
		}

		public function get1($table, $wc) {
			$sql = "SELECT * FROM $table";
			return parent::query($sql, $wc);
		}

		public function get2($table, $sel, $wc) {
			$sql = "SELECT " . implode(",", $sel) . " FROM $table";
			return parent::query($sql, $wc);
		}

		public function getSiblings($do_obj) {
			$clazz = get_class($do_obj);
			$sql = "SELECT * FROM " . $clazz::$TABLE;
			$wc = $do_obj->parentIdClause();
			return parent::query($sql, $wc);
		}
	}

	global $DAO;
	$DAO = new SmyleDAO();

?>
