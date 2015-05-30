<?php

require_once 'config.php';

$data_error = ''; 
if( isset($_SESSION['error_message']) ){
	$data_error = $_SESSION['error_message'];
	unset( $_SESSION['error_message'] ); 
}
if( isset($_SESSION['post']) ){
	extract($_SESSION['post']);
	/*    
	$login = $_SESSION['post']['login'];
	$name = $_SESSION['post']['name'];
	$pass = $_SESSION['post']['pass'];
	$pass_repeat = $_SESSION['post']['pass_repeat'];
	$email = $_SESSION['post']['email'];
	$date = $_SESSION['post']['date'];
	$sex = $_SESSION['post']['$sex'];
	*/    
	unset($_SESSION['post']);
}

// если была отравка формы то пытаемся авторизоваться
if(isset($_POST['submit'])){
	$valid_post = new validateData();
	$post           = $valid_post->trimArray($_POST);
	$login          = $valid_post->filterLogin( ( $post['login'] ) ? $post['login'] : '' );
	$name           = $valid_post->filterName( ( $post['name'] )  ? $post['name'] : '' , false );
	$pass           = $valid_post->filterPass( ( $post['pass'] ) ? $post['pass'] : '' );
	$pass_repeat    = $valid_post->filterPass( ( $post['pass_repeat'] ) ? $post['pass_repeat'] : '' );
	if( $pass != $pass_repeat ) $valid_post->addErrorMessage('пароли должны совпадать!');
	$email          = $valid_post->filterEmail( ($post['email'] ) ? $post['email'] : '' );
	$date           = $valid_post->filterDate( $post['date'], false );  
	$sex            = $valid_post->filterSex( ($post['sex'] ) ? $post['sex'] : '' );   
	// загрузка юзерпика
	if($_FILES && $_FILES['file']['name']){
		$UPLOAD = new fileUpload('file');
		if( $upload_error = $UPLOAD->getError('', '') ){
			$valid_post->addErrorMessage($upload_error, '', '');
		}else{
			$userpic = $UPLOAD->_uploaded[0]; 
		}        
	}


	// если есть ошибки то идем на форму регистрации
	if( $valid_post->getErrorMessage('<div class = "alert alert-danger" >') ){
		@unlink($userpic);
		$_SESSION['error_message'] = $valid_post->getErrorMessage('<div class = "alert alert-danger" >');
		$_SESSION['post'] = $post;
		header('Location:'.$_SERVER['PHP_SELF']); exit; 
	}

	//  регистрируем нового юзера
	$newUser = newUser::getInstance();
	$newUser->setNewUser($login, $name, $pass, $email, $date, $sex, $userpic);
	if(!$user = $newUser->addNewUser()){
		// если неудача то идем на форму регистрации
		@unlink($userpic);
		$_SESSION['error_message'] = $newUser->getErrorMessage('<div class = "alert alert-danger" >');
		$_SESSION['post'] = $post; 
		header('Location:'.$_SERVER['PHP_SELF']); exit();
	}else{
		// идем на вход, и заходим через COOKIE
		$_SESSION['user'] = $user;
		header('Location:login.php'); exit();

	}




}


?>
<!DOCTYPE html>
<html lang="ru">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Регистрация нового пользователя</title>
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
			<section class="row register">

				<div class="col-sm-6 col-sm-offset-3">

					<form enctype="multipart/form-data" action="<?=$_SERVER['PHP_SELF'] ?>" onSubmit="return validateForm(this)" method="POST" role="form">

						<div class="panel panel-default">
							<div class="panel-heading">Для продолжения работы с сервисом необходимо зарегистрироваться или &nbsp;<a href="login.php" class="login_link"><span class="glyphicon glyphicon-hand-right"></span>&nbsp;войти в аккаунт</a>
								<br />

							</div>
							<div class="panel-body">
								<div id="lahg_trigger" class="lang_trigger">
									<a onClick="return langTrigger('RU')" class="active">RU</a>
									<a onClick="langTrigger('EN')" class="">EN</a>
									<span class="glyphicon glyphicon-hand-left"></span>&nbsp;select English language  
								</div>                               
								<h3 class="legend">Регистрация нового пользователя&nbsp;</h3>

								<div class="alert alert-info">
									<?=( isset($data_error) ) ? $data_error : '' ?>
									<span class="glyphicon glyphicon-asterisk" ></span>&nbsp;<span>поля обязательные для заполнения</span>
								</div>

								<div class="form-group">
									<label for="login">Логин&nbsp;</label><span class="example">логин может содержать большие и маленькие латинские буквы и цифры, начинаться обязательно с буквы</span>
									<div class="input-group">
										<span class="input-group-addon"><span class="glyphicon glyphicon-asterisk"></span></span><input type="text" name="login" value="<?=isset($login)? $login : '' ?>" class="form-control" id="login" placeholder="Введите логин" required="required" tabindex="1">
									</div>
								</div>


								<div class="form-group">
									<label for="name">Имя&nbsp;</label><span class="example">имя может содержать большие и маленькие буквы латиницей и кириллицей</span>
									<div class="input-group">
										<span class="input-group-addon"><span class="glyphicon glyphicon-asterisk grey"></span></span><input type="text" name="name" value="<?=isset($name)? $name : '' ?>" class="form-control" id="name" placeholder="Введите имя" tabindex="2">
									</div>
								</div>								



								<div class="form-group">
									<label for="pass">Пароль&nbsp;</label><span class="example">обязательно должны присутствовать большие и маленькие буквы латинского алфавита, а также цифры</span>
									<div class="input-group">
										<span class="input-group-addon"><span class="glyphicon glyphicon-asterisk"></span></span><input type="password" name="pass" value="<?=isset($pass)? $pass : '' ?>" class="form-control" id="pass" placeholder="Введите пароль"required="required" tabindex="3">
									</div>
								</div>

								<div class="form-group">
									<label for="pass_repeat">Повторите пароль&nbsp;</label><span class="example">Пароли должны совпадать</span>
									<div class="input-group">
										<span class="input-group-addon"><span class="glyphicon glyphicon-asterisk"></span></span><input type="password" name="pass_repeat" value="<?=isset($pass_repeat)? $pass_repeat : '' ?>" class="form-control" id="pass_repeat" placeholder="Повторите пароль" required="required" tabindex="4">
									</div>
								</div>

								<div class="form-group">
									<label for="email">E-mail&nbsp;</label><span class="example">email@example.com</span>
									<div class="input-group">
										<span class="input-group-addon"><span class="glyphicon glyphicon-asterisk" ></span></span><input type="email" name="email" value="<?=isset($email)? $email : '' ?>" class="form-control" id="email" placeholder="Введите e-mail" tabindex="5">
									</div>
								</div>

								<div class="form-group">
									<label for="date">Дата рождения&nbsp;</label><span class="example">в формате дд-мм-гггг</span>
									<div class="input-group">
										<span class="input-group-addon"><span class="glyphicon glyphicon-asterisk grey"></span></span><input type="date" name="date" value="<?=isset($date)? $date : '' ?>" class="form-control" id="date" placeholder="дд-мм-гггг" tabindex="6" >
									</div>
								</div>

								<label>Пол&nbsp;</label>
								<div class="form-group-inline sex">
									<!--label -->
									<div class="input-group">
										<input type="radio" name="sex" id="sex1" value="1" <?=(isset($sex) && $sex == '1')? 'checked' : '' ?> >
										<span>мужской&nbsp;</span>
										<!--/div>
										</label>
										<label>
										<div class="input-group"-->
										<input type="radio" name="sex" id="sex2" value="2" <?=(isset($sex) && $sex == '2')? 'checked' : '' ?> >
										<span>женский&nbsp;</span>
									</div>
									<!--/label-->
									<input class="hidden" type="radio" name="sex" id="sex3" value="0" <?=(!isset($sex) || $sex == '0')? 'checked' : '' ?> >                                  
								</div>

								<div class="form-group">
									<label for="date">Загрузить фото&nbsp;</label><span class="example">файл *.jpg, *.gif, *.png - имя файла может содержать буквы латиницей, цифры и нижнее подчеркивание</span>
									<div class="input-group">
										<span class="input-group-addon"><span class="glyphicon glyphicon-cloud-upload"></span></span><input type="file" accept="image/jpeg, image/png, image/gif" name="file" value="<?=isset($file)? $file : '' ?>" class="form-control" id="date" tabindex="7" >
									</div>
								</div>									

							</div><!-- panel-body -->




							<div class="panel-footer">
								<!--input type="hidden" name="submit" value="submit" -->
								<input type="hidden" name="lang" value="<?=(isset($lang)) ? $lang : 'ru' ?>" >
								<button id="submitRegisterForm" name="submit" value="submit" type="submit" class="btn btn-primary" tabindex="7">Отправить данные</button> 
								<br /><strong> ИЛИ </strong>
								<p><a href="login.php" class=""><span class="glyphicon glyphicon-hand-right"></span>&nbsp;Войти в аккаунт</a></p>
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

<?php 

/*

else{
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


*/