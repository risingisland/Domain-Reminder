<?php

	session_start();
    require_once("includes/dbconnect.php"); //Load the settings
	require_once("includes/functions.php"); //Load the functions
	require_once("includes/languages.php"); //Load the langs
	$msg="";
	
	if (!isset($_SESSION["username"]) && isset($_COOKIE["username"])) {
		$_SESSION["username"] = $_COOKIE["username"];
	}
	if (!isset($_SESSION["username"])) {
		header("Location: index.php");
	exit();
	
	} else {

	$username 	= (!empty($_REQUEST["username"]))?strip_tags(str_replace("'","`",$_REQUEST["username"])):'';
	$adminEmail = (!empty($_REQUEST["adminEmail"]))?strip_tags(str_replace("'","`",$_REQUEST["adminEmail"])):'';
	$show_debug = (!empty($_REQUEST["show_debug"]))?strip_tags(str_replace("'","`",$_REQUEST["show_debug"])):'';
	$new_pass 	= (!empty($_REQUEST["new_pass"]))?strip_tags(str_replace("'","`",$_REQUEST["new_pass"])):'';
	$new_pass2 	= (!empty($_REQUEST["new_pass2"]))?strip_tags(str_replace("'","`",$_REQUEST["new_pass2"])):'';
	$adminLang 	= (!empty($_REQUEST["adminLang"]))?strip_tags(str_replace("'","`",$_REQUEST["adminLang"])):'';
	$show_domdata 	= (!empty($_REQUEST["show_domdata"]))?strip_tags(str_replace("'","`",$_REQUEST["show_domdata"])):'';
	
	if(!empty($_REQUEST["edit_settings"]) && $_REQUEST["edit_settings"]=="yes"){		
	
		if(!empty($username)){
			$sql="UPDATE adm_settings SET username='".$username."', show_debug='".$show_debug."'  WHERE id='1'";
			$result=mysqli_query($mysqli,$sql) or die("oopsy, error occured when tryin to save settings.");
			$msg.=' <div class="alert alert-success alert-dismissible">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
						<h5><i class="icon fas fa-check"></i> '.$lang['USERNAME_UPDATED'].'</h5>
					</div>';
		}
		
		if(!empty($adminEmail)){
			$sql="UPDATE adm_settings SET adminEmail='".$adminEmail."', show_debug='".$show_debug."'  WHERE id='1'";
			$result=mysqli_query($mysqli,$sql) or die("oopsy, error occured when tryin to save settings.");
			$msg.=' <div class="alert alert-success alert-dismissible">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
						<h5><i class="icon fas fa-check"></i> '.$lang['EMAIL_UPDATED'].'</h5>
					</div>';
		}
		
		if(!empty($adminLang)){
			$sql="UPDATE adm_settings SET adminLang='".$adminLang."', show_debug='".$show_debug."'  WHERE id='1'";
			$result=mysqli_query($mysqli,$sql) or die("oopsy, error occured when tryin to save settings.");
			$msg.=' <div class="alert alert-success alert-dismissible">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
						<h5><i class="icon fas fa-check"></i> '.$lang['CRON_LANG_UPDATED'].'</h5>
					</div>';
		}
		
		if(!empty($show_domdata)){
			$sql="UPDATE adm_settings SET show_domdata='".$show_domdata."', show_debug='".$show_debug."'  WHERE id='1'";
			$result=mysqli_query($mysqli,$sql) or die("oopsy, error occured when tryin to save settings.");
			$msg.=' <div class="alert alert-success alert-dismissible">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
						<h5><i class="icon fas fa-check"></i> '.$lang['SHOW_DOMAIN_DATA_UPDATED'].'</h5>
					</div>';
		}
			
		if(!empty($new_pass) && !empty($new_pass2)){
			if(md5($new_pass)==md5($new_pass2)){
				
				if($demo){
					$msg.=' <div class="alert alert-warning alert-dismissible">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
						<h5><i class="icon fas fa-exclamation-triangle"></i> Sorry, password cannot be changed in demo.</h5>
					</div>';
				} else {
					$sql="UPDATE adm_settings SET password='".md5($new_pass)."' WHERE id='1'";
					$result=mysqli_query($mysqli,$sql) or die("oopsy, error occured when tryin to save settings.");
					$msg.=' <div class="alert alert-success alert-dismissible">
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
								<h5><i class="icon fas fa-check"></i> '.$lang['PASSWORD_UPDATED'].'</h5>
							</div> ';
				}
			} else { 
				$msg.=' <div class="alert alert-warning alert-dismissible">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
							<h5><i class="icon fas fa-exclamation-triangle"></i> '.$lang['PASSWORD_NOT_MATCH'].'</h5>
						</div>';
			}
		}
						
	}
	
	//select settings from databse
	$sSQL = "SELECT id,username,adminEmail,adminLang,show_debug,show_domdata FROM adm_settings WHERE id='1'";
	$result = mysqli_query($mysqli,$sSQL) or die("err: " . mysqli_error($mysqli).$sSQL);
	if($row = mysqli_fetch_assoc($result)){
	  foreach($row as $key =>$value){ $$key=$value;}
	}
	mysqli_free_result ($result);
	
?>
<!DOCTYPE html>
<html lang="<?php echo $lang['LANG_CODE']; ?>">

	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title><?php echo $lang['SITE_TITLE']; ?></title>
		<link rel="icon" type="image/png" sizes="96x96" href="assets/img/domain.png">
		
		<!-- Google Font: Source Sans Pro -->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
		<!-- Font Awesome Icons -->
		<link rel="stylesheet" href="assets/js/fontawesome-free/css/all.min.css">
		<!-- Theme style -->
		<link rel="stylesheet" href="assets/css/adminlte.min.css"> 
	</head>

	<body class="hold-transition sidebar-mini">
		<div class="wrapper">
			<!-- Navbar -->
			<nav class="main-header navbar navbar-expand navbar-white navbar-light">
				<!-- Left navbar links -->
				<ul class="navbar-nav">
					<li class="nav-item"> <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a> </li>
				</ul>
				<!-- SEARCH FORM -->
				<!--form class="form-inline ml-3">
					<div class="input-group input-group-sm">
						<input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
						<div class="input-group-append">
							<button class="btn btn-navbar" type="submit"> <i class="fas fa-search"></i> </button>
						</div>
					</div>
				</form-->
				
				<!-- Right navbar links -->
				<ul class="navbar-nav ml-auto">
					<li class="nav-item dropdown">
						<a class="nav-link" data-toggle="dropdown" href="#">
							<i class="fas fa-user-circle fa-2x text-primary"></i>
						</a>
						<div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
							<span class="dropdown-header"><?php echo $lang['ADMIN']; ?></span>
						<div class="dropdown-divider"></div>
						<a href="settings.php" class="dropdown-item">
							<i class="fas fa-cogs mr-2  text-info"></i> <?php echo $lang['SETTINGS']; ?>
						</a>
						<div class="dropdown-divider"></div>
						<a href="logout.php" class="dropdown-item">
							<i class="fas fa-sign-out-alt mr-2  text-danger"></i> <?php echo $lang['SIGNOUT']; ?>
						</a>
					</li>
				</ul>
			</nav>
			<!-- /.navbar -->
			
			<!-- Main Sidebar Container -->
			<aside class="main-sidebar sidebar-dark-primary elevation-4">
				<!-- Brand Logo -->
				<a href="dashboard.php" class="brand-link"> <img src="assets/img/domain.png" alt="Logo" class="brand-image"> <span class="brand-text" style="color:#1E81CE;"><b><?php echo $lang['SITE_TITLE']; ?></b></span> </a>
				<!-- Sidebar -->
				<div class="sidebar">
					<!-- Sidebar user panel (optional) -->
					<div class="user-panel mt-3 pb-3 mb-3 d-flex">
						<div>&nbsp;</div>
					</div>
					
					<!-- Sidebar Menu -->
					<nav class="mt-2">
						<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
							<!-- Add icons to the links using the .nav-icon class
				   with font-awesome or any other icon font library -->
							<li class="nav-item">
								<a href="dashboard.php" class="nav-link"> <i class="nav-icon fas fa-tachometer-alt"></i>
									<p> <?php echo $lang['DASHBOARD']; ?> </p>
								</a>
							</li>
							<li class="nav-item">
								<a href="domains.php" class="nav-link"> <i class="nav-icon fas fa-globe"></i>
									<p> <?php echo $lang['DOMAINS']; ?> </p>
								</a>
							</li>
							<li class="nav-item">
								<a href="domains-expiring.php" class="nav-link"> <i class="nav-icon fas fa-flag"></i>
									<p> <?php echo $lang['EXPIRING_SOON']; ?> <span class="right badge badge-warning"><?php echo checkHowManyExpires();?></span> </p>
								</a>
							</li>
							<li class="nav-item">
								<a href="clients.php" class="nav-link"> <i class="nav-icon fas fa-users"></i>
									<p> <?php echo $lang['CLIENTS']; ?> </p>
								</a>
							</li>
							<li class="nav-item">
								<a href="clients-add.php" class="nav-link"> <i class="nav-icon fas fa-user-plus"></i>
									<p> <?php echo $lang['ADD_CLIENT']; ?> </p>
								</a>
							</li>
							<li class="nav-item">
								<a href="backup.php" class="nav-link"> <i class="nav-icon fas fa-database"></i>
									<p> <?php echo $lang['DATABASES']; ?> </p>
								</a>
							</li>
							<li class="nav-item">
								<a href="help.php" class="nav-link"> <i class="nav-icon fas fa-question-circle"></i>
									<p> <?php echo $lang['HELP']; ?> </p>
								</a>
							</li>
						</ul>
					</nav>
					<!-- /.sidebar-menu -->
				</div>
				<!-- /.sidebar -->
			</aside>
			<!-- Content Wrapper. Contains page content -->
			
			<div class="content-wrapper">
				<!-- Content Header (Page header) -->
				<div class="content-header">
					<div class="container-fluid">
						<div class="row mb-2">
							<div class="col-sm-12">
								<h1 class="m-0 text-primary"><i class="fas fa-cogs"></i> <?php echo $lang['SETTINGS']; ?></h1> 
							</div>
							<!-- /.col -->
						</div>
						<!-- /.row -->
					</div>
					<!-- /.container-fluid -->
				</div>
				
				
				<div class="content">
					<div class="container-fluid">
						<div class="row">
						
							<div class="col-md-6">
								<?php echo $msg; ?>
							</div>
							
							<!-- start tabs -->
							<div class="col-12">
								<div class="card card-primary card-outline card-tabs">
									<div class="card-header p-0 pt-1 border-bottom-0">
										<ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
											<li class="nav-item">
											<a class="nav-link active" id="custom-tabs-three-home-tab" data-toggle="pill" href="#custom-tabs-three-home" role="tab" aria-controls="custom-tabs-three-home" aria-selected="true"><strong><?php echo $lang['ADMIN']; ?></strong></a>
											</li>
											<li class="nav-item">
											<a class="nav-link" id="custom-tabs-three-profile-tab" data-toggle="pill" href="#custom-tabs-three-profile" role="tab" aria-controls="custom-tabs-three-profile" aria-selected="false"><strong><?php echo $lang['LANGUAGE']; ?></strong></a>
											</li>
										</ul>
									</div>
									
									<div class="card-body">
										<div class="tab-content" id="custom-tabs-three-tabContent">
											
											<div class="tab-pane fade show active" id="custom-tabs-three-home" role="tabpanel" aria-labelledby="custom-tabs-three-home-tab">
												<form action="settings.php" enctype="multipart/form-data" method="post" name="ff1" class="form-horizontal">
													<div class="card-body">
													
														<div class="form-group  row col-sm-6">
															<label><?php echo $lang['USERNAME']; ?>:</label>
															<div class="input-group">
																<div class="input-group-prepend">
																	<span class="input-group-text"><i class="fas fa-user text-primary"></i></span>
																</div>
																<input type="text" class="form-control" name="username" id="username" value="<?php echo $username?>" >
															</div>
														</div>
														
														<hr>
														
														<div class="form-group  row col-sm-6">
															<label><?php echo $lang['NEW_PASSWORD']; ?>:</label>
															<div class="input-group">
																<div class="input-group-prepend">
																	<span class="input-group-text"><i class="fas fa-key text-primary"></i></span>
																</div>
																<input type="password" class="form-control" name="new_pass" id="new_pass" placeholder="<?php echo $lang['PASSWORD']; ?>">
															</div>
														</div>
														<div class="form-group  row col-sm-6">
															<label></label>
															<div class="input-group">
																<div class="input-group-prepend">
																	<span class="input-group-text"><i class="fas fa-check-double text-primary"></i></span>
																</div>
																<input type="password" class="form-control" name="new_pass2" id="new_pass2" placeholder="<?php echo $lang['CONFIRM']; ?>">
															</div>
														</div>
														
														<hr>
														
														<div class="form-group  row col-sm-6">
															<label><?php echo $lang['EMAIL_FOR_CRON']; ?>:</label>
															<div class="input-group">
																<div class="input-group-prepend">
																	<span class="input-group-text"><i class="fas fa-envelope text-primary"></i></span>
																</div>
																<input type="text" class="form-control" name="adminEmail" id="adminEmail" value="<?php echo $adminEmail?>">
															</div>
														</div>
														
														<hr>
														
														<div class="form-grouprow col-sm-6">
															<label><?php echo $lang['CRON_NOTI_LANG']; ?>:</label>
															<div class="input-group">
																<div class="input-group-prepend">
																	<span class="input-group-text"><i class="fas fa-comment-dots text-primary"></i></span>
																</div>
																<input type="text" class="form-control" name="adminLang" id="adminLang" value="<?php echo $adminLang?>"> <span style="padding:10px 0 0 10px; vertical-align:text-bottom; color:grey;">(en, es, pl, etc.)</span>
															</div>
														</div>
														
														<hr>
														
														<div class="form-group row col-sm-6">
															<label for="inputEmail3" class="col-form-label"><i class="fas fa-receipt text-primary"></i> <?php echo $lang['SHOW_WHOIS']; ?></label>
														</div>
														
														<div class="form-group row col-sm-6">
															<div class="col-sm-2 offset-md-1">
																<input type="radio" class="form-check-input" name="show_debug" id="show_debug" value="1" <?php if($show_debug=="1"){ echo "checked"; }?> > <?php echo $lang['YES']; ?>
																
															</div>
															<div class="col-sm-2">
																<input type="radio" class="form-check-input" name="show_debug" id="show_debug" value="2" <?php if($show_debug=="2"){ echo "checked"; }?> > <?php echo $lang['NO']; ?>
															</div>
														</div>
														
														<hr>
														
														<div class="form-group row col-sm-6">
															<label for="inputEmail3" class="col-form-label"><i class="fas fa-server text-primary"></i> <?php echo $lang['SHOW_DOMAIN_DATA']; ?></label>
														</div>
														
														<div class="form-group row col-sm-6">
															<div class="col-sm-2 offset-md-1">
																<input type="radio" class="form-check-input" name="show_domdata" id="show_domdata" value="1" <?php if($show_domdata=="1"){ echo "checked"; }?> > <?php echo $lang['YES']; ?>
																
															</div>
															<div class="col-sm-2">
																<input type="radio" class="form-check-input" name="show_domdata" id="show_domdata" value="2" <?php if($show_domdata=="2"){ echo "checked"; }?> > <?php echo $lang['NO']; ?>
															</div>
														</div>
														
													</div>
													<!-- /.card-body -->
													<div class="card-footer col-sm-6">
														<button type="submit" name="create" id="create" class="btn btn-primary float-right"><?php echo $lang['UPDATE']; ?></button>
														<input value="yes" name="edit_settings" type="hidden" />
														<input value="<?php echo $id;?>" name="id" type="hidden" />
													</div>
													<!-- /.card-footer -->
												</form>
											</div>
											
											<div class="tab-pane fade" id="custom-tabs-three-profile" role="tabpanel" aria-labelledby="custom-tabs-three-profile-tab">
											
												<div class="row">
													<div class="col-sm-6">

														<div class="form-group col-sm-6">
														<label><i class="fas fa-language text-primary"></i> <?php echo $lang['CHOOSE_YOUR_LANG']; ?>:</label>
															<select class="form-control" name="lang-form" onchange="location = this.value;">
																<option> -- <?php echo $lang['LANG_LONG']; ?> -- </option>
																<?php  include("includes/lang-menu.php");?>
															</select>
														</div>
														
													</div>
												</div>

											</div>
											
										</div>
									</div>

								</div>
							</div>
							<!-- end tabs -->
							
						</div>
					</div>
				</div>
				
			</div>
			<!-- /.content-wrapper -->
			
			<!-- Main Footer -->
			<footer class="main-footer">
				<div class="float-right d-none d-sm-block">
					<p><b><?php echo $lang['VERSION']; ?>:</b> <?php echo $version; ?> </p>
				</div>
				<p><strong>Copyright &copy; <?php echo date("Y"); ?> <?php echo $lang['FOOTER_CREDITS']; ?></p>
			</footer>
			
		</div>
		<!-- ./wrapper -->
		
		<!-- REQUIRED SCRIPTS -->
		<!-- jQuery -->
		<script src="assets/js/jquery/jquery.min.js"></script>
		<!-- Bootstrap 4 -->
		<script src="assets/js/bootstrap/js/bootstrap.bundle.min.js"></script>
		<!-- AdminLTE App -->
		<script src="assets/js/adminlte.min.js"></script>
	</body>

</html>
<?php  } ?>