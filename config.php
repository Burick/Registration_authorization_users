<?php
session_start();
 /** 
 * 
 * Защита от брутфорса
 * $file = stat('/tmp/pswd.lock'); 
if ($file[9]-time() <= 2) showcapcha(); 
--- 
if (wrong_password) touch('/tmp/pswd.lock');



 */
define('CHARSET', 'UTF8');
define('DB_HOST', 'localhost');
define('DB_NAME', 'user_base');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_DSN', 'mysql:dbname='.DB_NAME.';host='.DB_HOST.';charset='.CHARSET.';');

define('COOKIE_NAME', 'mySCripCookie');
define('COOKIE_EXPIRE', time()+60*100);
define('COOKIE_PATH', '/');
define('COOKIE_DOMAIN', $_SERVER['HTTP_HOST'] );

define('IMAGES_PATH', 'upload/' );

	require_once 'class/Error.class.php';
	require_once 'class/Cookie.class.php';
	require_once 'class/DB.class.php';
	require_once 'class/fileUpload.class.php';
	require_once 'class/User.class.php';
	require_once 'class/newUser.class.php';
	require_once 'class/validateData.class.php';

