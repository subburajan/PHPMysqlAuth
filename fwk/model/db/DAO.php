<?php

	namespace com\smyle\model\db;

	final class DBH {

		private static $_instance;

		public static function getInstance() {
			if(!self::$_instance) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		private function __construct() {
			#print "DAO constructed";
		}


		public function getConnection() {
			include "db-conf.inc";
			$con = new \PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
			$con->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
			$con->setAttribute(\PDO::MYSQL_ATTR_INIT_COMMAND, 'SET character_set_client=utf8');
			$con->setAttribute(\PDO::MYSQL_ATTR_INIT_COMMAND, 'SET character_set_connection=utf8');
			$con->setAttribute(\PDO::MYSQL_ATTR_INIT_COMMAND, 'SET NAMES utf8');
			return $con;
		}
	}

	class DAOException extends \Exception {

		private $exception;

		public function __construct($message = null, $code = 0, $exception = null) {
			$this->$exception = $exception;
			parent::__construct($message, $code);
		}

		public function getException() {
			return $this->$exception;
		}
	}

	abstract class GenericDAO {

		protected $con;

		public function __construct() {

		}

		public function _insert($sql, $obj, $ai = 0) {
			try {
				$this->getCon();
				$stmt = $this->con->prepare($sql);
				foreach($obj as $k => $v) {
					bind($stmt, $k, $v);
				}
				$res = $stmt->execute();
				if($res && $ai) {
					$obj->{$ai} = $this->con->lastInsertId();
				}
				return $res;
			} catch(\PDOException $e) {
				echo $e; exit;

				throw $this->handleException($e);
			}
		}

		public function _update($sql, $map) {
			try {
				$this->getCon();
				$stmt = $this->con->prepare($sql);
				foreach($map as $k => $v) {
					bind($stmt, $k, $v);
				}
				$res = $stmt->execute();
				return $res;
			} catch(\PDOException $e) {
				throw $this->handleException($e);
			}
		}

		public function _executeUpdate($sql) {
			try {
				$this->getCon();
				$stmt = $this->con->prepare($sql);
				$res = $stmt->execute();
				return $res;
			} catch(\PDOException $e) {
				throw $this->handleException($e);
			}
		}

		public function query($sql, $whereClause = null) {
			if($whereClause != null) {
				$sql .= " WHERE " . $whereClause;
			}
			//echo $sql . "\n";
			try {
				$this->getCon();
				$stmt = $this->con->prepare($sql);
				$stmt->execute();
				$res = $stmt->fetchAll(\PDO::FETCH_OBJ);
				return $res;
			} catch(\PDOException $e) {
				throw $e;
				//throw $this->handleException($e);
			}
		}

		public function _delete($tableName, $whereClause = null) {
			$sql = "DELETE FROM " . $tableName;
			if($whereClause) {
				$sql .= " WHERE " . $whereClause;
			}
			try {
				$this->getCon();
				$stmt = $this->con->prepare($sql);
				$res = $stmt->execute();
				return $res;
			} catch(\PDOException $e) {
				throw $this->handleException($e);
			}
		}

		public function close() {
			$this->con = NULL;
		}

		protected function getCon() {
			if(is_null($this->con)) {
				$this->con = DBH::getInstance()->getConnection();
			}
		}

		protected function handleException($e) {
			$this->con = NULL;
			$msg = "DAO Exception: ". $e->getMessage();
			return new DAOException($msg, 1, $e);
		}
	}

	function bind($stmt, $k, $v) {
		$stmt->bindParam($k, $v);
	}
?>
