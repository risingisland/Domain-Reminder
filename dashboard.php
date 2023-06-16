<?php

	session_start();
    require_once("includes/dbconnect.php"); //Load the settings
	require_once("includes/functions.php"); //Load the functions
	require_once("includes/languages.php"); //Load the langs
	$msg="";
	
	if($_SESSION["logged_in"]!=true){
		header("Location: index.php");
		
	} else {

		$table='';
		//prepare expiring 35 days table
		$date_30 = date("Y-m-d",strtotime(date("Y-m-d")." +35 days"));
		//PAGES TABLE  GENERATION TO SHOW IN HTML BELOW
		$sql="SELECT * FROM adm_domains WHERE renewalDate<='".$date_30."' ORDER BY renewalDate ASC";
		$result=mysqli_query($mysqli,$sql) or die("error getting domains from db");
		
		if(mysqli_num_rows($result)>0){
			while($rr=mysqli_fetch_assoc($result)){
				$table .="<tr>";
				$table .= "<td><a href=\"domains-edit.php?id=".$rr["id"]."\">".$rr["domain"]."</a></td>";
				$table .= "<td>".date("d M, Y",strtotime($rr["renewalDate"]))."</td>";
				$table .= "</tr>";
			} 
		} else {
			$table .="<tr><td colspan=\"2\">".$lang['0_DOMAINS_EXPIRING']."</td></tr>";
		} 
		
		$cal='';
		//prepare expiring calendar
		$date_180 = date("Y-m-d",strtotime(date("Y-m-d")." +180 days"));
		$date_365 = date("Y-m-d",strtotime(date("Y-m-d")." +365 days"));
		//PAGES TABLE  GENERATION TO SHOW IN HTML BELOW
		$sql="SELECT * FROM adm_domains WHERE renewalDate<='".$date_365."' ORDER BY renewalDate ASC";
		$result=mysqli_query($mysqli,$sql) or die("error getting domains from db");
		
		if(mysqli_num_rows($result)>0){
			while($rr=mysqli_fetch_assoc($result)){
				$cal .= '{';
				$cal .= 'startDate: "'.date("Y-m-d",strtotime($rr["renewalDate"])).'",';
				$cal .= 'endDate: "'.date("Y-m-d",strtotime($rr["renewalDate"])).'",';
				$cal .= 'summary: " <a href=\"domains-edit.php?id='.$rr["id"].'\">'.$rr["domain"].' </a>"';
				$cal .= '},';
			} 
		} else {
			$cal .="{},";
		} 

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
		<!-- Calendar -->
		<link rel="stylesheet" href="assets/js/simple-calendar/simple-calendar.css">
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
								<a href="dashboard.php" class="nav-link active"> <i class="nav-icon fas fa-tachometer-alt"></i>
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
								<h1 class="m-0 text-primary"><i class="fas fa-tachometer-alt"></i> <?php echo $lang['DASHBOARD']; ?></h1>
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
							
							<div class="col-lg-5">
								<div class="card card-primary card-outline">
									<div class="card-header">
										<div id="container" class="calendar-container"></div>
									</div>
								</div>
							</div>
						
							<div class="col-lg-7">
								<div class="card card-warning card-outline">
									<div class="card-header">
										<h5 class="m-0"><i class="fas fa-exclamation-triangle text-warning"></i> <?php echo $lang['EXPIRING_X_DAYS']; ?></h5>
									</div>
									<div class="card-body">
									
										<div class="fixTableHeadAdmin">
											<table class="table table-striped xtable-sm">
												<thead>
													<tr>
														<th><?php echo $lang['DOMAIN_NAME']; ?></th>
														<th><?php echo $lang['EXPERATION_DATE']; ?></th>
													</tr>
												</thead>

												<tbody>
													<?php echo $table; ?>
												</tbody>
											</table>
										</div>
									
										<div style="padding-top:20px;">
											<a href="domains-expiring.php" class="btn btn-primary float-right"><?php echo $lang['VIEW_MORE']; ?></a>
										</div>
									</div>
								</div>
							</div>
							
						</div>
					</div>
				</div>
				
			</div>
			<!-- /.content-wrapper -->
			
			<!-- Main Footer -->
			<footer class="main-footer">
				<div class="float-right d-none d-sm-block">
					<p><b><?php echo $lang['VERSION']; ?>:</b> <?php echo $lang['VERSION_NO']; ?> </p>
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
		<!-- Calendar -->
		<script src="assets/js/simple-calendar/jquery.simple-calendar.min.js"></script>
		<script>
			$(document).ready(function(){
				$("#container").simpleCalendar({
					//Defaults options below
					//string of months starting from january
					months: ['<?php echo $lang['JAN']; ?>','<?php echo $lang['FEB']; ?>','<?php echo $lang['MAR']; ?>','<?php echo $lang['APR']; ?>','<?php echo $lang['MAY']; ?>','<?php echo $lang['JUN']; ?>','<?php echo $lang['JUL']; ?>','<?php echo $lang['AUG']; ?>','<?php echo $lang['SEP']; ?>','<?php echo $lang['OCT']; ?>','<?php echo $lang['NOV']; ?>','<?php echo $lang['DEC']; ?>'],
					days: ['<?php echo $lang['SUN']; ?>','<?php echo $lang['MON']; ?>','<?php echo $lang['TUE']; ?>','<?php echo $lang['WED']; ?>','<?php echo $lang['THU']; ?>','<?php echo $lang['FRI']; ?>','<?php echo $lang['SAT']; ?>'],
					displayYear:		true,	// Display year in header
					fixedStartDay:		true,	// Week begin always by monday
					displayEvent:		true,	// Display existing event
					disableEventDetails: false,	// disable showing event details
					disableEmptyDetails: true,	// disable showing empty date details
					events: [
					
						<?php echo $cal; ?>{}
						
					],	// List of events
					onInit:			function (calendar) {},		// Callback after first initialization
					onMonthChange:	function (month, year) {},	// Callback on month change
					onDateSelect:	function (date, events) {},	// Callback on date selection
					onEventSelect:	function() {},	// Callback on event selection - use $(this).data('event') to access the event
					onEventCreate:	function( $el ) {},	// Callback fired when an HTML event is created - see $(this).data('event')
					onDayCreate:	function( $el, d, m, y ) {}	// Callback fired when an HTML day is created   - see $(this).data('today'), .data('todayEvents')
				});
			});
		</script>
		<?php //include('includes/domains-delete.php');?>
	</body>

</html>
<?php } ?>