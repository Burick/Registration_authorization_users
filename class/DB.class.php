<?php
/**
* Class для соединения с базой MySQL через PDO библиотеку
* должен быть файл config.php
* с установленными константами
* DB_NAME, DB_HOST, DB_USER, DB_PASSWORD, CHARSET
*/

class DB{
	/**
	* Singleton instance
	* @var DB
	*/
	private static $instance;

	/**
	*Соединение с базой
	* @var PDO 
	*/
	private static $connection;

	public $_DB_OPTIONS = array(
		PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
		PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES "UTF8"'
	);    
	/**
	* приватный конструктор Singelton
	*/
	private function __construct() {
		try{
			self::$connection = new PDO(DB_DSN, DB_USER, DB_PASSWORD, $this->_DB_OPTIONS);  
		}catch(PDOException $e){
			echo 'ошибка соеднения с базой';
		}

	}

	/**
	*возвращает инстанс класса DB 
	* @return DB
	*/
	public static function getInstance(){
		if(empty(self::$instance)){
			self::$instance = new DB();
		}
		return self::$instance;
	}

	/**
	* возвращает соединение PDO с базой
	* @return PDO
	*/
	public static function getConn(){
		self::getInstance();
		return self::$connection;
	}

	/**
	* Подготавливает SQL к исполнению
	* @param String $sql
	* @return PDOStatment stmt
	*/
	public static function prepare($sql){
		return self::getConn()->prepare($sql);
	}

	/**
	* возвращает id последнего INSERT
	* @return int
	*/
	public static function lastInsertId(){
		return self::getConn()->lastInsertId();
	}

	/**
	* начало транзакции
	* @return bool
	*/
	public static function beginTransaction(){
		return self::getConn()->beginTransaction();
	}

	/**
	* выполнение транзакции
	* @return bool
	*/
	public static function commit(){
		return self::getConn()->commit();
	}

	/**
	* отмена транзакции
	* @return bool
	*/
	public static function rollback(){
		return self::getConn()->rollback();
	}
	
	/**.
	* исполнение запроса
	* @param mixed $sql
	* @return bool
	*/
	public function executeSQL($sql='') {
		try{
			$sth = self::prepare($sql);
			$sth->execute(); 
		}catch(PDOException $e){
			echo 'Ошибка при выполнении запроса к базе';
			return false;
		}
		return $sth;    
	}   
} // class DB




