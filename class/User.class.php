<?php
/**
* Class пользователя  выполнен по шаблону Singelton
* требует ккласс для соединения с базой
* и класс для работы с COOKIE
* @TODO сделать сбор шибок в класс ERROR
*/ 
class User {
	protected $_DB               = null;
	protected $_browser_cookie   = null;
	protected $_user             = null;
	protected $_error_mesage     = null;
	public 	  $_COOKIE           = null;

	final public static function getInstance()
	{
		static $instance = null;

		if (null === $instance)
		{
			$instance = new static();
		}

		return $instance;
	}

	final protected function __clone() {}
	protected function __construct() {
		try{
			$this->_DB = DB::getInstance();  
		}catch(Exception $e){
			echo 'Не удалось соединиться с базой';
		}
		$this->_COOKIE  = new Cookie(COOKIE_NAME);
	}
	/**
	* добавление ошибки, позже создал класс для сбора ошибок
	* @TODO убрать методы для работы с ошибками и заменить на класс ERROR
	* 
	* @param String $message
	*/
	protected function addErrorMessage ($message = '') {

		$this->_error_mesage .= '<p>'.$message.'</p>';
		return $this->_error_mesage;
	}

	public function getErrorMessage ( $prefix = '<div>', $suffix ='</div>' ) {
		return ($this->_error_mesage) ? $prefix.$this->_error_mesage.$suffix : '';
	}    
	######################################################
	/**
	* метод входа пользователя
	* 
	* @param String $login
	* @param String $pass
	*/
	public function userLogIn($login, $pass) {
		$user_ip = $_SERVER['REMOTE_ADDR'];
		$time = date('Y-m-d H:i:s', time());
		$pass = md5(md5($pass));
		$hash = md5($login.$pass.$time.$user_ip);

		$sql =  "SELECT   user_info.`user_id`, `login`, `name`, `mail`, `date_birth`, `sex`, `pic`, `pass`, `hash`, `user_ip`, `last_login_time`
		FROM `user_info`
		JOIN user_pic
		on user_info.`user_id` = user_pic.`user_id`
		LEFT JOIN user_session
		on user_info.`user_id` = user_session.`user_id` 
		WHERE `login` = '$login' AND `pass` = '$pass'         
		";
		$sth = $this->_DB->executeSQL($sql);
		if($this->_user = $sth->fetch(PDO::FETCH_ASSOC)){
			$this->userUpdate($this->_user['user_id'], $hash, $user_ip, $time);           
			$this->setUserCookie($hash);
		}else{
			$this->addErrorMessage('неправильный логин или пароль');
		}
		return $this->_user;
	}
	/**
	* добавление в базу фото пользователя
	* 
	* @param mixed $user_id
	* @param String $pic
	*/
	public function updateUserPic ($user_id = null, $pic = null) {
		$sql = "UPDATE user_pic 
		SET `pic` = '$pic' 
		WHERE `user_id` = '$user_id'
		";
		return $this->_DB->executeSQL($sql);   
	}
	/**
	* метод входа через COOKIE
	* в таблице user_session изменяется хеш 
	* и изменяется информация в COOKIE
	* @param String $hash
	* @return Array $user
	*   
	*/
	public function userCookieLogIn($hash = null ) {
		if(!$hash) return false;

		$user_ip = $_SERVER['REMOTE_ADDR'];
		$time = date('Y-m-d H:i:s', time());

		$sql =  "SELECT   user_info.`user_id`, `login`, `name`, `mail`, `date_birth`, `sex`, `pic`, `pass`, `hash`, `user_ip`, `last_login_time`
		FROM `user_info`
		JOIN user_pic
		on user_info.`user_id`= user_pic.`user_id`
		JOIN user_session
		on user_info.`user_id`=user_session.`user_id` 
		WHERE `hash`='$hash'        
		";
		$sth = $this->_DB->executeSQL($sql);
		$this->_user = $sth->fetch(PDO::FETCH_ASSOC); 

		if($this->_user){
			$hash = md5($this->_user['login'].$this->_user['pass'].$time.$user_ip);
			$this->setUserCookie($hash);
			$this->userUpdate($this->_user['user_id'], $hash, $user_ip, $time);
		}else{

			$this->_COOKIE->delete();
			/*           
			$this->_COOKIE->_value = '';
			$this->_COOKIE->_expire = time() - 3600;
			$this->setUserCookie();
			*/           
		}  
		return $this->_user;
	}

	/**
	* обновляет COOKIE
	* @param Stirng $hash
	* @return bool
	*/
	protected function setUserCookie($hash = '') {
		$this->_COOKIE->_value = $hash;
		/*        
		$this->_COOKIE->_expire = COOKIE_EXPIRE;
		$this->_COOKIE->_path = COOKIE_PATH;
		$this->_COOKIE->_domain = COOKIE_DOMAIN;
		*/        
		return $this->_COOKIE->save();
	}

	/**
	* put your comment there...
	*     
	* @param integer    $user_id
	* @param String     $hash
	* @param Ip      $user_ip
	* @param datetime   $time
	* @return bool
	*/
	public function userUpdate($user_id, $hash = null, $user_ip = null, $time = null) {
		$sql = "UPDATE user_session 
		SET `hash` = '$hash', `user_ip` = '$user_ip', `last_login_time` = '$time' 
		WHERE `user_id` = '$user_id'
		";
		return  $this->_DB->executeSQL($sql);
	}

	/**
	* логаут пользователя   
	*/
	public function userLogOut() {
		/*
		$this->_COOKIE->_value = '';
		$this->_COOKIE->_expire = time() - 3600;
		$this->_COOKIE->_path = COOKIE_PATH;
		$this->_COOKIE->_domain = COOKIE_DOMAIN;        
		$this->_COOKIE->save();
		*/      
		$this->_COOKIE->delete();
		session_unset($_SESSION);
		return session_destroy();


	}
}// #################   User   ################




