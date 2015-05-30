<?php
/**
* класс создания нового пользователя 
* наследует класс пользователя
* требует User.class.php
*/
class newUser extends User{

	// логин пользователя
	public $_login = '';
	// имя пользователя
	public $_name = '';
	// пароль пользователя
	public $_pass = '';
	// E-mail
	public $_email = '';
	// пол
	public $_sex = '';
	// дата рождения в формате YYYY-MM-DD
	public $_date = '';
	// фото пользователя
	public $_userpic = '';

	//private $_new_user_id = null;

	/**
	* получение данных о новом пользователе
	* 
	* @param String $login
	* @param String $name
	* @param String $pass
	* @param E-mail $email
	* @param Date $date  в формате  дата в формате YYYY-MM-DD
	* @param integer $sex
	* @param String $userpic
	*/
	public function setNewUser ($login = '', $name = '', $pass = '', $email = '', $date = '', $sex = '', $userpic = '' ) {

		$this->_login       = $login;
		$this->_name        = $name;
		$this->_pass        = $pass;
		$this->_email       = $email;
		$this->_sex         = $sex;
		$this->_date        = $date;
		$this->_userpic     = $userpic;	
	}

	/**
	* добавление нового пользователя, запись в базу
	* @return mixed
	*/
	public function addNewUser () {
		//проверка на уникальность
		if(!$this->checkUniqueUser()) return false;
		$pass = md5(md5($this->_pass));

		$user_ip = $_SERVER['REMOTE_ADDR'];
		$time = date('Y-m-d H:i:s', time());
		// хеш для COOKIE
		$hash = md5($this->_login.$pass.$time.$user_ip);

		$sql = "INSERT INTO user_info (`login`, `name`, `mail`, `date_birth`, `sex`, `pass` ) 
		VALUES ('$this->_login', '$this->_name', '$this->_email', '$this->_date', '$this->_sex', '$pass');
		SELECT @last := LAST_INSERT_ID();
		INSERT INTO user_session (`user_id`,`hash`, `user_ip`, `last_login_time`) VALUES(@last, '$hash', '$user_ip', '$time' );
		INSERT INTO user_pic VALUES (@last, '$this->_userpic');        
		";		
		if($this->_DB->executeSQL($sql)){ 
			if($this->_user = $this->userLogIn($this->_login, $this->_pass)){
				return $this->_user;
			}else{
				return false;
			}
		}else{
			return false;
		}

	}// addNewUser ()

	/**
	* Проверка пользователя на уникальность
	* 
	* @param String $login
	* @param E-mail $mail
	* @return bool
	*/
	public function checkUniqueUser ($login = null, $mail = null) {
		// проверка уникальности логина
		if(!$unic_log = $this->checkUniqueLogin($login)) $this->addErrorMessage('этот логин уже используется, выберите другой');   
		// проверка уникальности E-mail
		if(!$unic_mail = $this->checkUniqueMail($mail)) $this->addErrorMessage('этот E-mail уже используется, выберите другой');
		if(!$unic_log || !$unic_mail) return false;    
		return true;
	}

	/**
	* Проверка уникальности E-mail
	* 
	* @param E-mail $email
	* @return bool
	*/
	public function checkUniqueMail ($email = null) {
		if(!$email) $email = $this->_email;
		$sql = "SELECT COUNT(*) 
		FROM `user_info`
		WHERE `mail` = '$email'
		";         
		$sth = $this->_DB->executeSQL($sql);
		$res = $sth->fetch(PDO::FETCH_NUM);
		return ($res[0]*1) ? false : true;        
	}
	/**
	* Проверка уникальности логина
	* 
	* @param String $login
	* @return bool
	*/
	public function checkUniqueLogin ($login = null) {
		if(!$login) $login = $this->_login;
		$sql = "SELECT COUNT(*) 
		FROM `user_info`
		WHERE `login` = '$login'
		";
		$sth = $this->_DB->executeSQL($sql);
		$res = $sth->fetch(PDO::FETCH_NUM); 
		return ($res[0]*1) ? false : true;        
	}

	/**
	* Загрузка фото пользователя
	* @return mixed
	*/
	public function uploadUserPic () {
		$userpic = new fileUpload('file');
		if( !$error = $fileUpload->getError() ){
			return $userpic->_uploaded[0];
		}else{
			$this->_error_mesage .=  $fileUpload->getError();
			return false;
		}  
	}

}//class newUser

