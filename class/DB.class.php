<?php
/*Class to MySQL connect via PDO library

* need file config.php to define the constants DB_NAME, DB_HOST, DB_USER and 
* DB_PASSWORD
*   
*/

class DB{
	/**
	Singleton instance
	@var DB 
	**/
	private static $instance;

	/**
	Conection with database
	@var PDO
	**/
	private static $connection;

	public $_DB_OPTIONS = array(
		PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
		PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES "UTF8"'
	);    
	/**
	Constructor  private of singleton class
	**/
	private function __construct() {
		self::$connection = new PDO('mysql:dbname='.DB_NAME.';host='.DB_HOST.';charset='.CHARSET.';', DB_USER, DB_PASSWORD, $this->_DB_OPTIONS);
//		self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//		self::$connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
	}

	/**
	get instance of class Connection
	@return type
	**/
	public static function getInstance(){
		if(empty(self::$instance)){
			self::$instance = new DB();
		}
		return self::$instance;
	}

	/**
	Return a connection PDO with database
	@return PDO
	**/
	public static function getConn(){
		self::getInstance();
		return self::$connection;
	}

	/**
	Prepare the SQL to execute
	@param String $sql
	@return PDOStatment stmt
	**/
	public static function prepare($sql){
		return self::getConn()->prepare($sql);
	}

	/**
	Retur the id of last search INSERT
	@return int
	**/
	public static function lastInsertId(){
		return self::getConn()->lastInsertId();
	}

	/**
	start of trasaction
	@return bol
	**/
	public static function beginTransaction(){
		return self::getConn()->beginTransaction();
	}

	/**
	commit of transaction
	@return bol 
	**/
	public static function commit(){
		return self::getConn()->commit();
	}

	/**
	rollback of transaction
	@return bool
	**/
	public static function rollback(){
		return self::getConn()->rollback();
	}

	/**
	format date to MySQL (05/12/2015 to 2015-12-05)
	@param type $date
	@return type
	**/
	public static function dateToMySql($date){
		return implode('-', array_reverse(explode('/', $date)));
	}

	/**
	Format date from MySQL (2015-12-05 to 05/12/2015)
	@param type $date
	@return type 
	**/
	public static function dateFromMySql($date){
		return implode('/', array_reverse(explode('-', $date)));
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $sql
	*/
	 public function executeSQL($sql='') {
		try{
			$sth = self::prepare($sql);
			$sth->execute();
		}catch(PDOException $e){
			return $e->getMessage();
		}    
		return  $sth;
	}   
} // class DB




