<?php
	
	error_reporting(E_ALL);

	// Set session lifetime to 90 days (in seconds)
	$secondsPerDay = 24 * 60 * 60;
	$sessionLifetime = 90 * $secondsPerDay;

	// Set session cookie lifetime
	session_set_cookie_params($sessionLifetime);

	// Set session garbage collection lifetime
	ini_set('session.gc_maxlifetime', $sessionLifetime);

	// Start the session
	session_start();

    require_once("includes/dbconnect.php"); //Load the settings
	require_once("includes/functions.php"); //Load the functions
	require_once("includes/languages.php"); //Load the langs
	$msg="";
	
	//LOGIN VARIABLES
	$username = (!empty($_REQUEST['username']))?strip_tags(str_replace("'","`",$_REQUEST['username'])):'';
	$password = (!empty($_REQUEST['password']))?strip_tags(str_replace("'","`",$_REQUEST['password'])):'';
	
	// LOGIN
	if(!empty($_REQUEST["login"]) && $_REQUEST['login']=="yes"){
		if($username=="" || $password=="")
		{
			$msg = '<div class="alert alert-warning alert-dismissible">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
							<h5><i class="icon fas fa-exclamation-triangle"></i> '.$lang['ALERT'].'</h5>
						'.$lang['WRONG_LOGIN'].'
					</div>';
			
		} else {
		
			$sSQL = "SELECT * FROM `adm_settings` WHERE `username`='".$username."'";
			$result = mysqli_query($mysqli,$sSQL) or die("Invalid query: " . mysqli_error($mysqli));
			if(mysqli_num_rows($result)>0){
				$row=mysqli_fetch_assoc($result);
	
				if(md5($password)!=$row["password"]){
						$msg = '<div class="alert alert-warning alert-dismissible">
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
										<h5><i class="icon fas fa-exclamation-triangle"></i> '.$lang['ALERT'].'</h5>
									'.$lang['WRONG_LOGIN'].'
								</div>';
				} else {
				
					$_SESSION['idUser']= $row["id"];
					$_SESSION['username']= $row["username"];
					$_SESSION['accesslevel']= 1899;
					$_SESSION['logged_in'] = true;
				}
				
			} else {
				$msg = '<div class="alert alert-warning alert-dismissible">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
								<h5><i class="icon fas fa-exclamation-triangle"></i> '.$lang['ALERT'].'</h5>
							'.$lang['WRONG_LOGIN'].'
						</div>';
			}
		} 
	}
	
	if(isset($_SESSION["logged_in"]) && $_SESSION["logged_in"]==true){ 
		header("Location: dashboard.php");
	} else {
		
?>
<!DOCTYPE html>
<html lang="<?php echo $lang['LANG_CODE']; ?>">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Log in | <?php echo $lang['SITE_TITLE']; ?></title>
		<link rel="icon" type="image/png" sizes="96x96" href="assets/img/domain.png">
		
		<!-- Google Font: Source Sans Pro -->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
		<!-- Font Awesome -->
		<link rel="stylesheet" href="assets/js/fontawesome-free/css/all.min.css">
		<!-- icheck bootstrap -->
		<link rel="stylesheet" href="assets/js/icheck-bootstrap/icheck-bootstrap.min.css">
		<!-- Theme style -->
		<link rel="stylesheet" href="assets/css/adminlte.min.css">
		<link rel="stylesheet" href="assets/css/custome.css">
	</head>
	
	<body class="hold-transition login-page">
		<div class="login-box">
			<div class="login-logo">
				<img src="assets/img/domain.png" alt="Logo" class="brand-image" style="width:48px;"> <b style="color:#1E81CE;"><?php echo $lang['SITE_TITLE']; ?></b>
			</div>
			<!-- /.login-logo -->
			<div class="card">
				<div class="card-body login-card-body">
					<?php echo $msg; ?>
					<p class="login-box-msg"><?php echo $lang['SIGNIN_TO_START']; ?></p>

					<form method="post" action="index.php" enctype="multipart/form-data" name="ff1" id="login-form">
						<div class="input-group mb-3">
							<input type="text" id="username" name="username" placeholder="<?php echo $lang['USERNAME']; ?>" class="form-control"  autofocus>
							<div class="input-group-append">
								<div class="input-group-text">
									<span class="fas fa-user"></span>
								</div>
							</div>
						</div>
						<div class="input-group mb-3">
							<input type="password" id="password" name="password" class="form-control" placeholder="<?php echo $lang['PASSWORD']; ?>">
							<div class="input-group-append">
								<div class="input-group-text">
									<span class="fas fa-lock"></span>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-8">
								<div class="icheck-primary">
									<input type="checkbox" id="savelogin" name="remember_me">
									<label for="savelogin">
									<?php echo $lang['REMEMBER_ME']; ?>
									</label>
								</div>
							</div>
							<!-- /.col -->
							<div class="col-4">
								<button type="submit" value="Submit" class="btn btn-primary btn-block"><?php echo $lang['SIGNIN']; ?></button>
								<input type="hidden" value="yes" name="login"  />
							</div>
							<!-- /.col -->
						</div>
					</form>
				</div>
				<!-- /.login-card-body -->
			</div>
		</div>
		<!-- /.login-box -->

		<!-- jQuery -->
		<script src="assets/js/jquery/jquery.min.js"></script>
		<!-- Bootstrap 4 -->
		<script src="assets/js/bootstrap/js/bootstrap.bundle.min.js"></script>
		<!-- AdminLTE App -->
		<script src="assets/js/adminlte.min.js"></script>

		<script src="assets/js/setcookie.js"></script>

	</body>
</html>
<?php  } ?>