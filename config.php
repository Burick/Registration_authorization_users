<?php  error_reporting(E_ALL ^ E_NOTICE);
session_start();
/**
* Файл настроек
*/
// параметры для подключения к базе
define('CHARSET', 'UTF8');
define('DB_HOST', 'localhost');
define('DB_NAME', 'user_base');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_DSN', 'mysql:dbname='.DB_NAME.';host='.DB_HOST.';charset='.CHARSET.';');
// COKIE по умолчанию
define('COOKIE_NAME', 'mySCripCookie');
define('COOKIE_EXPIRE', time()+24*60*60*2);
define('COOKIE_PATH', '/');
define('COOKIE_DOMAIN', $_SERVER['HTTP_HOST'] );
define('COOKIE_SECURE', FALSE );
define('COOKIE_HTTPOLY', TRUE );
// Папка для загрузки картинок
define('IMAGES_PATH', 'upload/' );
define('IMAGES_MAXSIZE', 512000 );

function __autoload ($class_name) {
	require_once 'class/'.$class_name.'.class.php';
}


function cookieLogin() {
  // COOKIE то авторизовуеся через них
  if(isset($_COOKIE[COOKIE_NAME])){
	  $USER = User::getInstance();
	  $user = $USER->userCookieLogIn($_COOKIE[COOKIE_NAME]);
	  if($user){
		  $_SESSION['user'] = $user;
		  header('Location:profile.php'); exit;       
	  }
  } 
}



/*
	require_once 'class/Error.class.php';
	require_once 'class/Cookie.class.php';
	require_once 'class/DB.class.php';
	require_once 'class/fileUpload.class.php';
	require_once 'class/User.class.php';
	require_once 'class/newUser.class.php';
	require_once 'class/validateData.class.php';
*/