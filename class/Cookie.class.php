<?php

class Cookie{
	/**
	* Класс обвертка для управления COOKIE
	* перед использованием cookie 
	* нужно открыть сессию 
	* session_start()
	* 
	* @example $cookie = new Cookie('myCOOK','cookieValue') 
	* @example $coocie->save() ставим cookie
	* -----------------------
	* @example $cookie = new Cookie('myCOOK')  читаем cookie
	*  -----------------------
	* @example $cookie->delete()  удаляем cookie
	* 
	* @method __consrtact()
	* @method save()  записывает 
	* @method delete() удалякт
	* @method exits()  проверяет установку
	* 
	*/




	/**
	* Имя COOKIE
	* cookiename
	* @var string
	*/
	public $_name 		= '';

	/**
	* значение $_COOKIE['cookiename'].
	* 
	* @var string
	*/
	public $_value 		= '';

	/**
	* time()+60*60*24*30 установит срок действия cookie 30 дней.
	* Если задать 0 или пропустить этот аргумент, 
	* срок действия cookie истечет с окончанием сессии (при закрытии броузера).
	* 
	* принимает в качестве значения метку времени Unix,
	* а хранит его в формате Wdy, DD-Mon-YYYY HH:MM:SS GMT.
	* PHP делает внутреннее преобразование автоматически. 
	* 
	* @var timestamp
	*/
	public $_expire 	= COOKIE_EXPIRE;

	/**
	* Путь к директории на сервере, из которой будут доступны cookie.
	* Если задать '/', cookie будут доступны во всем домене domain.
	* Если задать '/foo/', cookie будут доступны только из директории /foo/
	* и всех ее поддиректорий (например, /foo/bar/) домена domain.
	* По умолчанию значением является текущая директория, в которой cookie устанавливается. 
	*  
	* @var string
	*/
	public $_path 		= COOKIE_PATH;

	/**
	* Домен, которому доступны cookie.
	* Задание домена 'www.example.com' сделает cookie доступными в поддомене www
	* и поддоменах более высоких порядков.
	* Cookie доступные низким уровням, таким как 'example.com',
	* будут доступны во всех поддоменах высших уровней, с том числе 'www.example.com'
	* 
	* @var string
	*/
	public $_domain 	= COOKIE_DOMAIN;

	/**
	* Указывает на то, что значение cookie должно передаваться
	* от клиента по защищенному HTTPS соединению.
	* Если задано TRUE, cookie от клиента будет передано на сервер,
	* только если установлено защищенное соединение.
	* При передаче cookie от сервера клиенту следить за тем, чтобы cookie 
	* этого типа передавались по защищенному каналу
	* (стоит обратить внимание на $_SERVER["HTTPS"]).
	* 
	* @var bool
	*/
	public $_secure 	= false;

	/**
	* Если задано TRUE, cookie будут доступны только через HTTP протокол.
	* То есть cookie в этом случае не будут доступны скриптовым языкам, вроде JavaScript
	* 
	* @var bool 
	*/
	public $_httponly 	= true;

	/**
	* Задает параметры для cookie 
	* или подхватывает старый если параметр $value небыл задан
	* и если cookie был раннее yстановлен 
	*/
	public function __construct($name = null, $value = null, $expire = null, $path = null, $domain = null, $secure = null, $httponly = null){
		if(($this->_name = (string) $name)){
			if(!is_null($value)){
				$this->_value     = (string) $value;
				is_null($expire)    ?:  $this->_expire 	= (int) $expire;
				is_null($path)      ?:  $this->_path    = (string) $path;
				is_null($domain)    ?    $this->_domain = $_SERVER['HTTP_HOST']  :  $this->_domain     = (string) $domain;
				is_null($secure)    ?:  $this->_secure  = (bool) $secure;
				is_null($_httponly) ?:  $this->_secure 	= (bool) $_httponly;
			} else {
				$this->_value = $this->exists() ? $_COOKIE[$this->_name] : '';
			}
		} else {
			throw new Exception('неправильное имя cookie');
		}
	}

	/**
	* Проверяем наличие COOKIE
	* @return boolean
	*/
	public function exists(){
		return isset($_COOKIE[$this->_name]);
	}
	
	/**
	* Если перед вызовом функции клиенту уже передавался какой-либо вывод
	* (тэги, пустые строки, пробелы, текст и т.п.), setcookie() вызовет отказ и вернет FALSE.
	* Если setcookie() успешно отработает, то вернет TRUE.
	* Это, однако, не означает, что клиентское приложение (броузер) правильно приняло
	* и обработало cookie. 
	* @return boolean 
	*/
	public function save(){
		return setcookie($this->_name, $this->_value, $this->_expire, $this->_path, $this->_domain, $this->_secure, $this->_httponly);
	}
	/**
	* Удаляет cookie параметр value передается пустой 
	* @return bool 
	*/
	public function delete(){
		return setcookie($this->_name, '', time() - 2*24*60*60, $this->_path, $this->_domain, $this->_secure, $this->_httponly);
	}
}