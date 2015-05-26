<?php
require_once 'config.php';

if( isset($_GET['auth']) && $_GET['auth'] == 'logout' ){
	
	$USER = User::getInstance();
	$USER->userLogOut();
	header('Location:login.php'); exit;
}

if(isset($_SESSION['user'])){
	header('Location:profile.php'); exit;
}

$data_error = ''; 
if( isset($_SESSION['error_message']) ){
	$data_error = $_SESSION['error_message'];
	unset( $_SESSION['error_message'] ); 
}
if( isset($_SESSION['post']) ){
	$login = $_SESSION['post']['login'];
	$pass = $_SESSION['post']['pass'];
	unset($_SESSION['post']);
}


if(isset($_COOKIE[COOKIE_NAME])){
	$USER = User::getInstance();
	$user = $USER->userCookieLogIn($_COOKIE[COOKIE_NAME]);
	if($user){
		$_SESSION['user'] = $user;
		header('Location:profile.php'); exit;       
	}
}


if(isset($_POST['submit'])){
	$valid_post     = new validateData();
	$post           = $valid_post->trimArray($_POST);
	$login          = $valid_post->filterLogin( ( isset($post['login']) ) ? $post['login'] : '' );
	$pass           = $valid_post->filterPass( ( isset($post['pass']) ) ? $post['pass'] : '' );
	if( $valid_post->getErrorMessage('<div class = "alert alert-danger" >') ){
		$data_error = $valid_post->getErrorMessage('<div class = "alert alert-danger" >');
		extract($post); 
	}else{
		$USER = User::getInstance();
		if( !$user = $USER->userLogIn($login, $pass) ){
			$_SESSION['post'] = $post;
			$_SESSION['error_message'] = $USER->getErrorMessage('<div class = "alert alert-danger" >');
			if(isset($_SESSION['user']))unset($_SESSION['user']);
			header('Location:'.$_SERVER['PHP_SELF']); exit;
		}else{
			//$_SESSION['user'] = $user;
			//header('Location:profile.php'); exit;
			header('Location:'.$_SERVER['PHP_SELF']); exit;
		}
	}  	

}




?><!DOCTYPE html>
<html lang="ru">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Вход в сервис</title>
		<!-- Bootstrap CSS -->
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link href="css/style.css" rel="stylesheet">
		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>
	<body>
		<div class="container">
			<section class="row login_form">
				<div class="col-sm-4 col-sm-offset-4">

					<form action="<?=$_SERVER['PHP_SELF'] ?>" method="POST" role="form">
						<div class="panel panel-default">
							<div class="panel-heading">
								<?=(isset($data_error)) ? $data_error.'<br />' : '' ?>
								Введите логин и пароль указанные при регистрации, чтобы продолжить работу с сервисом или <a href="registration.php" class="registration_link">зарегистрируйтесь</a>
							</div>
							<div class="panel-body">
								<legend>Вход</legend>
								<div class="form-group">
									<label for="login">Логин</label>
									<div class="input-group">
										<span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span><input type="text" name="login" value="<?=isset($login)? $login : '' ?>" class="form-control" id="login" placeholder="Введите логин">
									</div>
								</div>
								<div class="form-group">
									<label for="pass">Пароль</label>
									<div class="input-group">
										<span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span><input type="password" name="pass" value="<?=isset($pass)? $pass : '' ?>" class="form-control" id="pass" placeholder="Введите пароль">
									</div>
								</div>

							</div>
							<div class="panel-footer">
								<input type="hidden" name="submit" value="submit" >
								<button type="submit"  value="submit" class="btn btn-primary">Войти</button> <a href="registration.php" class="pull-right"><span class="glyphicon glyphicon-hand-right"></span>&nbsp;Регистрация</a>
							</div>
						</div>
					</form>
				</div>
			</section>


		</div>

		<!-- JavaScript -->
		<script src="js/script.js"></script>
	</body>
</html>