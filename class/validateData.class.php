<?php
/**
* сласс валидации данных формы
* @TODO переделать сбор шибок через класс ERROR
* 
*/
class validateData{
	const REGEXP_LOGIN  = '/^[a-zA-Z][a-zA-Z0-9-_\.]{2,20}$/';
	const REGEXP_NAME   =  '/^([а-яА-ЯёЁьыa-zA-Z])+$/' ;
	const REGEXP_PASS   = '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).*$/' ;
	const REGEXP_DATE   = '/(19|20)\d\d-((0[1-9]|1[012])-(0[1-9]|[12]\d)|(0[13-9]|1[012])-30|(0[13578]|1[02])-31)/' ;  // дата в формате YYYY-MM-DD

	protected $_error_mesage = '';

	public function addErrorMessage ($message = '', $prefix = '<p>', $suffix = '</p>') {

		$this->_error_mesage .= $prefix.$message.$suffix;
		return $this->_error_mesage;
	}

	public function getErrorMessage ( $prefix = '<div>', $suffix ='</div>' ) {
		return ($this->_error_mesage) ? $prefix.$this->_error_mesage.$suffix : '';
	}
	#######################################

	/**
	* перебирает массив и удаляет пробелы вначале и в конце 
	* 
	* @param Array $array
	* @return array
	*/
	public function trimArray ($array) {
		foreach($array as $k=>$v){
			$array[$k] = trim($v);
		}
		return $array;
	}
	
	/**
	* проверка логин по регулярке
	* 
	* @param String $string
	* @return String или False при ошибке
	*/
	public function filterLogin ($string) {
		$string = $this->filterData($string, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => self::REGEXP_LOGIN)));
		if(!$string) $this->addErrorMessage('проверьте поле &laquo;Логин&raquo;!<br />');
		return $string; 
	}

	/**
	* проверка имени пользователя по регулярке
	* 
	* @param String $string
	* @param Bool $need True если проверка обязательна
	* @return String или False при ошибке
	*/
	public function filterName ($string, $need = true) {
		if(!$need && trim($string) == '') return $string;
		$string = $this->filterData($string, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => self::REGEXP_NAME)));
		if(!$string) $this->addErrorMessage('проверьте поле &laquo;Имя&raquo;!<br />');
		return $string; 
	}

	/**
	* проверка пароль по регулярке
	* 
	* @param String $string
	* @return String или False при ошибке
	*/    
	public function filterPass ($string) {
		$string = $this->filterData($string, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => self::REGEXP_PASS)));
		if(!$string) $this->addErrorMessage('проверьте поле &laquo;Пароль&raquo;!<br />');
		return $string; 
	}

	/**
	* проверка E-mail через встроенную функцию PHP
	* 
	* @param E-mail $string
	* @return String или False при ошибке
	*/   
	public function filterEmail ($string) {
		$string = $this->filterData($string, FILTER_VALIDATE_EMAIL);
		if(!$string) $this->addErrorMessage('проверьте поле &laquo;E-Mail&raquo;!<br />');
		return $string; 
	}
		
	/**
	* проверка даты по регулярке
	* 
	* @param String $string
	* @param Bool $need True если проверка обязательна
	* @return String или False при ошибке
	*/
	public function filterDate ($string, $need = true) {
		if(!$need && trim($string) == '') return $string;  
		$string = $this->filterData($string, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => self::REGEXP_DATE)));
		if(!$string) $this->addErrorMessage('проверьте поле &laquo;Дата рождения&raquo;!<br />');
		return $string; 
	}
	
	/**
	* проверка поля "Пол" 
	* и нормализация к типу integer
	* 
	* @param String $string
	* @return String или False при ошибке
	*/
	public function filterSex ($string) {
		if($string*1 > 2) return false;
		return $string*1;
	}

	/**
	* проверка любых данных в виде строки 
	* функцией filter_var()
	* 
	* @param String $string
	* @param int $filter
	* @param Array $options  
	* @return String или False при ошибке
	*/
	public function filterData ($string, $filter = FILTER_DEFAULT, $options = array() ) {

		return filter_var(trim($string), $filter, $options);

	}

} // class validateData




