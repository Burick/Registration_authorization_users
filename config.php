<?php
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

function __autoload ($class_name) {
	require_once 'class/'.$class_name.'.class.php';
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