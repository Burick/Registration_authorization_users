<?php
// require_once '../config.php';
class newUser extends User{

	public $_login = '';
	public $_name = '';
	public $_pass = '';
	public $_email = '';
	public $_sex = '';
	public $_date = '';
	public $_userpic = '';

	private $_new_user_id = null;


	public function setNewUser ($login = '', $name = '', $pass = '', $email = '', $date = '', $sex = '', $userpic = '' ) {

		$this->_login       = $login;
		$this->_name        = $name;
		$this->_pass        = $pass;
		$this->_email       = $email;
		$this->_sex         = $sex;
		$this->_date        = $date;
		$this->_userpic     = $userpic;	
	}

	public function addNewUser () {
		if(!$this->checkUniqueUser()) return false;
		$pass = md5(md5($this->_pass));

		$user_ip = $_SERVER['REMOTE_ADDR'];
		$time = date('Y-m-d H:i:s', time());
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

	public function checkUniqueUser ($login = null, $mail = null) {
		if(!$unic_log = $this->checkUniqueLogin($login)) $this->addErrorMessage('этот логин уже используется, выберите другой');   
		if(!$unic_mail = $this->checkUniqueMail($mail)) $this->addErrorMessage('этот E-mail уже используется, выберите другой');
		if(!$unic_log || !$unic_mail) return false;    
		return true;
	}

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

