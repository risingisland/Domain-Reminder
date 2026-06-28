<?php

	session_start();
    require_once("includes/dbconnect.php"); //Load the settings
	require_once("includes/functions.php"); //Load the functions
	require_once("includes/languages.php"); //Load the langs
	require_once("config/version.php"); //Load the version number
	
	$msg="";
	
	if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {
		header("Location: index.php"); exit();
	} else {
	//get access level
	
	$filter = "";  //default filter variable. getting rid of undefined variable exception.
	$bgClass="even"; // default first row highlighting CSS class
	$files_table = ""; //var with php generated html table.
	//sorting
	$_SESSION["adm_domains.orderBy"]= (!empty($_REQUEST["orderBy"]))?$_REQUEST["orderBy"]:(!empty($_SESSION["adm_domains.orderBy"])?$_SESSION["adm_domains.orderBy"]:"renewalDate");
	$_SESSION["adm_domains.direction"]= (!empty($_REQUEST["direction"]))?$_REQUEST["direction"]:(!empty($_SESSION["adm_domains.direction"])?$_SESSION["adm_domains.direction"]:'ASC');

	$allowed_cols = ['domain','clientID','registrationDate','renewalDate','registrar','id'];
	$orby = in_array($_SESSION['adm_domains.orderBy'], $allowed_cols) ? $_SESSION['adm_domains.orderBy'] : 'renewalDate';
	$didi = ($_SESSION['adm_domains.direction'] === 'DESC') ? 'DESC' : 'ASC';
	
	$filter .= " ORDER BY $orby $didi";
	
	$date_30 = date("Y-m-d",strtotime(date("Y-m-d")." +30 days"));
	$date_60 = date("Y-m-d",strtotime(date("Y-m-d")." +60 days"));	
	$date_90 = date("Y-m-d",strtotime(date("Y-m-d")." +90 days"));	
	
	//PAGES TABLE  GENERATION TO SHOW IN HTML BELOW
	$exp_stmt=$pdo->prepare("SELECT * FROM adm_domains WHERE renewalDate <= ? $filter"); $exp_stmt->execute([$date_90]);
	$exp_rows=$exp_stmt->fetchAll();
	if(count($exp_rows)>0){
		foreach($exp_rows as $rr){
			 		  
			if($rr["renewalDate"]<=$date_30){ $bgClass="light-red"; } else
			if($rr["renewalDate"]>$date_30 && $rr["renewalDate"]<=$date_60 ){ $bgClass="light-yellow"; } else
			if($rr["renewalDate"]>$date_60 && $rr["renewalDate"]<=$date_90 ){ $bgClass="light-green"; }
			  
			$editable='<a href="domains-edit.php?id='.$rr["id"].'"><i class="fas fa-edit"></i></a>';  

			$files_table .="<tr class=\"".$bgClass."\">"; // background-color
			
			$files_table .= '<td><a href="domains-edit.php?id='.$rr["id"].'">'.$rr["domain"]."</a></td>";
			$files_table .= "<td>".getClientName($rr["clientID"])."</td>";
			$files_table .= "<td>".(!empty($rr["registrationDate"]) && $rr["registrationDate"] !== "0000-00-00" ? date("d M, Y", strtotime($rr["registrationDate"])) : "------")."</td>";
			$files_table .= "<td>".(!empty($rr["renewalDate"]) && $rr["renewalDate"] !== "0000-00-00" ? date("d M, Y", strtotime($rr["renewalDate"])) : "------")."</td>";
			$files_table .= "<td>".$rr["registrar"]."</td>";
			
			$files_table .= "<td>".$editable." </td>";
			if(trim($rr["renew_link"])!=""){
				$files_table .= '<td> <a href="'.$rr['renew_link'].'" target="_blank"><span class="right badge badge-info"> '.$lang['RENEW'].' <i class="fas fa-sync"></i></span></a></td></tr>';
			} else {
				$files_table .= '<td>&nbsp;</td></tr>';
			}
		} // end of all files from db query (end of while loop)
		
	} else { 
		//0 files found in database. ( end of IF mysqli_num_rows > 0 )
		$files_table .= '<tr><td colspan="7">'.$lang['0_DOMAINS_IN_DB'].'</td></tr>';
	} 

	$direction=($_SESSION["adm_domains.direction"]=='DESC')?('ASC'):('DESC');
	
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
		<link rel="stylesheet" href="assets/css/custome.css"> 
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
								<a href="domains-expiring.php" class="nav-link active"> <i class="nav-icon fas fa-flag"></i>
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
								<h1 class="m-0 text-primary"><i class="fas fa-flag"></i> <?php echo $lang['EXPIRING_SOON']; ?></h1> 
							</div>
							<!-- /.col -->
						</div>
						<!-- /.row -->
					</div>
					<!-- /.container-fluid -->
				</div>
				
				<style>
					.light-green{background-color:rgba(0, 204, 0, .2)}
					.light-yellow{background-color:rgba(255, 204, 0, .2)}
					.light-red{background-color:rgba(255, 0, 0, .2)}
				</style>
				
				<section class="content">
					
					<div class="col-md-6">
						<?php echo $msg; ?>
					</div>
					
					<div class="col-lg-12">
						<div class="card card-warning card-outline">
							<div class="card-body p-0">
								
								<form enctype="multipart/form-data" action="expiring-soon.php" method="post" name="ff2">
									<input type="hidden" value="yes" name="files_delete" />
					
									<div class="fixTableHead">
										<table class="table table-hover">
											<thead>
												<tr>
													<th>
														<strong><a onclick="document.ff2.orderBy.value='domain'; document.ff2.direction.value='<?php echo $direction?>'; document.ff2.submit();" style="cursor:pointer;"><?php echo $lang['DOMAIN_NAME']; ?></a>&nbsp;<?php if($_SESSION["adm_domains.orderBy"]=="domain"){ ?><img src="assets/img/<?php echo strtolower($direction)?>.png" /><?php } ?></strong>
													</th>
											
													<th>
														<strong><a onclick="document.ff2.orderBy.value='clientID'; document.ff2.direction.value='<?php echo $direction?>'; document.ff2.submit();" style="cursor:pointer;"><?php echo $lang['CLIENT_NAME']; ?></a>&nbsp;<?php if($_SESSION["adm_domains.orderBy"]=="clientID"){ ?><img src="assets/img/<?php echo strtolower($direction)?>.png" /><?php } ?></strong>
													</th>
											
													<th>
														<strong><a onclick="document.ff2.orderBy.value='registrationDate'; document.ff2.direction.value='<?php echo $direction?>'; document.ff2.submit();" style="cursor:pointer;"><?php echo $lang['REGISTRATION_DATE']; ?></a>&nbsp;<?php if($_SESSION["adm_domains.orderBy"]=="registrationDate"){ ?><img src="assets/img/<?php echo strtolower($direction)?>.png" /><?php } ?></strong>
													</th>
											
													<th>
														<strong><a onclick="document.ff2.orderBy.value='renewalDate'; document.ff2.direction.value='<?php echo $direction?>'; document.ff2.submit();" style="cursor:pointer;"><?php echo $lang['RENEWAL_DATE']; ?></a>&nbsp;<?php 								if($_SESSION["adm_domains.orderBy"]=="renewalDate"){ ?><img src="assets/img/<?php echo strtolower($direction)?>.png" /><?php } ?></strong>
													</th>
											
													<th>
														<strong><a onclick="document.ff2.orderBy.value='registrar'; document.ff2.direction.value='<?php echo $direction?>'; document.ff2.submit();" style="cursor:pointer;"><?php echo $lang['REGISTRAR']; ?></a>&nbsp;<?php if($_SESSION["adm_domains.orderBy"]=="registrar"){ ?><img src="assets/img/<?php echo strtolower($direction)?>.png" /><?php } ?></strong>
													</th>
											
													<th><?php echo $lang['EDIT']; ?></th>
													<th>&nbsp;</th>
												</tr>
											</thead>
											
											<tbody>
												<?php echo $files_table; ?>
											</tbody>
											
										</table>
									</div>
					
									<input type="hidden" name="orderBy" value="<?php echo htmlspecialchars($_SESSION["adm_domains.orderBy"]); ?>">
									<input type="hidden" name="direction" value="<?php echo htmlspecialchars($_SESSION["adm_domains.direction"]); ?>">
									
								</form>
								
							</div>
						</div>
					</div>
					
					<div class="col-lg-12">
						<div class="card card-info card-outline">
							<div class="card-header">
								<h5 class="m-0 text-info"><i class="fas fa-info-circle"></i> <?php echo $lang['NOTE']; ?>:</h5> 
							</div>
							<div class="card-body">
								<ul>
									<li><span style="background-color: #FFE2DD;">[<?php echo $lang['RED']; ?>]</span> - <?php echo $lang['RED_TEXT']; ?></li>
									<li><span style="background-color: #FFFFCC;">[<?php echo $lang['YELLOW']; ?>]</span> - <?php echo $lang['YELLOW_TEXT']; ?></li>
									<li><span style="background-color: #E6FDC6;">[<?php echo $lang['GREEN']; ?>]</span> - <?php echo $lang['GREEN_TEXT']; ?></li>
								</ul>
								
								<p class="card-text"><?php echo $lang['RENEW_TEXT']; ?></p>
								
								<div style="padding-top:20px;">
									<a href="#" onClick="MyWindow=window.open('cron.php?cron=do&d=45','MyWindow','width=600,height=300'); return false;" class="btn btn-info float-right"><i class="fas fa-paper-plane"></i> <?php echo $lang['SEND_NOTICE']; ?></a>
								</div>
							</div>
						</div>
					</div>
				</section>
							
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
<?php }?>