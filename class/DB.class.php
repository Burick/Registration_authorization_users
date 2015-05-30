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
		}catch(Exception $e){
			/**
			* @todo убрать вывод ошибок для дебага 
			*/
			echo 'ошибка соеднения с базой '.DB_NAME.'<br /> Хост: '.DB_HOST.'<br /> User: '.DB_USER.'<br />Pass: '.DB_PASSWORD.'<br />';
			echo '<pre>'
			.$e->getMessage()
			.'<br />in File '.$e->getFile()
			.'<br />in Lile '.$e->getLine()
			.'<br/>Trace '.$e->getTrace()
			.'<br />Trace as String '.$e->getTraceAsString()
			.'</pre>----------------------------------------------<br/ ><br />';
			return $e;
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
	*/
	public function executeSQL($sql='') {
		try{
			$sth = self::prepare($sql);
			return $sth->execute();
		}catch(PDOException $e){
			echo 'ERROR '.$e->getMessage();
			return false;
		}    
	}   
} // class DB




