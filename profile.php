<?php
require_once 'config.php';

if(isset($_SESSION['user'])){
  extract ( $_SESSION['user'], EXTR_PREFIX_ALL, $prefix = 'profile' );
	
}else{
   exit(header('Location:login.php?login=false')); 
}

?><!DOCTYPE html>
<html lang="ru">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Добро пожаловать <?=isset($profile_login)? $profile_login : ''  ?></title>
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
			<section class="row profile">
				<div class="col-sm-6 col-sm-offset-3">
					<div class="panel panel-default">
						<div class="panel-heading">
							<p class="welcom">Добро пожаловать <strong><?=isset($profile_name)? $profile_name: '';  ?> (<?=isset($profile_login)? $profile_login: ''  ?>)</strong></p>
							<p class="last_visit">Дата последнего входа: <?=isset($profile_last_login_time)? date('d.m.Y  в  H:i', strtotime($profile_last_login_time)): '' ?></p>
							<p class="row"><a class="btn btn-primary pull-right" href="login.php?auth=logout">Выход</a></p>
						</div>

						<div class="panel-body">
							<div class="row">
								<div class="col-sm-6">
									<div class="user_pic"><img class="img-responsive" src="<?=(isset($profile_pic) && $profile_pic)? $profile_pic: IMAGES_PATH.'nophoto.jpg' ?>" alt="" /></div>
									<div><?=(isset($profile_pic) && $profile_pic == '' )? '<p class="load"><span class="glyphicon glyphicon-cloud-upload"></span> <button type="button" class="btn btn-sm btn-warning">загрузить фото</button></p>' : ''?></div>   
								</div>
								<div class="col-sm-6">
									<p class="name"><span class="glyphicon glyphicon glyphicon-user"></span>Имя: <?=isset($profile_name)? $profile_name: '' ?></p>
									<p class="date_birth"><span class="glyphicon glyphicon-calendar"></span>Дата рождения: <?=isset($profile_date_birth)? date('d.m.Y', strtotime($profile_date_birth)): ''; ?></p>
									<p class="user_sex"><span class="glyphicon glyphicon-info-sign"></span>Пол: <?=isset($profile_sex)? (($profile_sex == '1')? 'мужской': 'женский') : '' ?></p>

								</div>
							</div>

						</div>

						<div class="panel-footer">
							<p>Текущий IP <?=isset($profile_user_ip)? $profile_user_ip: '' ?></p>
						</div>
					</div>
				</div>
			</section>
		</div>

		<!--div>
			<p><a href="http://oop.local/test_task/login.php">Логин</a></p>
			<p><a href="http://oop.local/test_task/profile.php">Профайл</a></p>
			<p><a href="http://oop.local/test_task/loader.php">Loader</a></p>
			<?='<br /> <pre>' ?>
			<?='<br />$_POST&mdash;'; print_r($_POST) ?>
			<?='<br />$_GET&mdash;'; print_r($_GET) ?>
			<?='<br />$_SESION&mdash;'; print_r($_SESSION) ?>
			<?='<br />$_COOKIE&mdash;'; print_r($_COOKIE) ?>
			<?='<br /> </pre>' ?>
		</div-->
		<!-- JavaScript -->
		<script src="js/script.js"></script>
	</body>
</html>