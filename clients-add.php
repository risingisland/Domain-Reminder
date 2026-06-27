<?php

	session_start();
    require_once("includes/dbconnect.php"); //Load the settings
	require_once("includes/functions.php"); //Load the functions
	require_once("includes/languages.php"); //Load the langs
	require_once("config/version.php"); //Load the version number
	
	$msg  = "";
	$msg1 = "";
	$msg2 = "";
    $msg3 = "";
	
	if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {
		header("Location: index.php"); exit();
	} else {
	
		//show page only if admin access level
		
		//request all neccessary variables for user update action.
		$id				= (!empty($_REQUEST["id"]))?strip_tags(str_replace("'","`",$_REQUEST["id"])):'';
		$name			= (!empty($_REQUEST["name"]))?strip_tags(str_replace("'","`",$_REQUEST["name"])):'';
		$company		= (!empty($_REQUEST["company"]))?strip_tags(str_replace("'","`",$_REQUEST["company"])):'';
		$comment		= (!empty($_REQUEST["comment"]))?str_replace("'","`",$_REQUEST["comment"]):'';
		
		$job_title		= (!empty($_REQUEST["job_title"]))?strip_tags(str_replace("'","`",$_REQUEST["job_title"])):'';
		$address		= (!empty($_REQUEST["address"]))?strip_tags(str_replace("'","`",$_REQUEST["address"])):'';
		$web			= (!empty($_REQUEST["web"]))?strip_tags(str_replace("'","`",$_REQUEST["web"])):'';
		$phone			= (!empty($_REQUEST["phone"]))?strip_tags(str_replace("'","`",$_REQUEST["phone"])):'';
		$email			= (!empty($_REQUEST["email"]))?strip_tags(str_replace("'","`",$_REQUEST["email"])):'';
		$renew_link2	= (!empty($_REQUEST["renew_link2"]))?strip_tags(str_replace("'","`",$_REQUEST["renew_link2"])):'';
		$domain2		= (!empty($_REQUEST["domain2"]))?strip_tags(str_replace("'","`",$_REQUEST["domain2"])):'';
		if(!empty($domain2)){ $domain2 = str_replace("https://","",$domain2); $domain2 = str_replace("http://","",$domain2); $domain2 = str_replace("www.","",$domain2); $domain2 = trim($domain2,"/");}
		$registrar2		= (!empty($_REQUEST["registrar2"]))?strip_tags(str_replace("'","`",$_REQUEST["registrar2"])):'';
		$renewalDate2	= (!empty($_REQUEST["renewalDate2"]))?strip_tags(str_replace("'","`",$_REQUEST["renewalDate2"])):'';
		$clientID2		= (!empty($_REQUEST["clientID2"]))?strip_tags(str_replace("'","`",$_REQUEST["clientID2"])):'';
		$comment2		= (!empty($_REQUEST["comment2"]))?str_replace("'","`",$_REQUEST["comment2"]):'';
		$autoWhois2		= (!empty($_REQUEST["autoWhois2"]))?strip_tags(str_replace("'","`",$_REQUEST["autoWhois2"])):'';
		
		$registrationDate2 = (!empty($_REQUEST["registrationDate2"]))?strip_tags(str_replace("'","`",$_REQUEST["registrationDate2"])):'';
		if($renewalDate2=="YYYY-MM-DD"){ $renewalDate2="0000-00-00"; }
		if($registrationDate2=="YYYY-MM-DD"){ $registrationDate2="0000-00-00"; }

	
		//"edit page" action processing.
		if(!empty($_REQUEST["edit_page"]) && $_REQUEST["edit_page"]=="yes" && !empty($name)){
					
				if(empty($id)){
					$ins_c = $pdo->prepare("INSERT INTO adm_clients (dateCreated,name,company,comment,job_title,phone,address,email,web) VALUES (datetime('now'),?,?,?,?,?,?,?,?)");
					$ins_c->execute([$name,$company,$comment,$job_title,$phone,$address,$email,$web]); $id = $pdo->lastInsertId();
					$msg = '<div class="alert alert-success alert-dismissible">
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
								<h5><i class="icon fas fa-check"></i> '.$lang['CLIENT_SUCCESS_ADDED'].'</h5>
							</div>';
				}
				
				$msg .= '<div class="alert alert-success alert-dismissible">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
							<h5><i class="icon fas fa-check"></i> '.$lang['CLIENT_SUCCESS_UPDATED'].'</h5>
						</div>';		
				$upd_c = $pdo->prepare("UPDATE adm_clients SET name=?,company=?,comment=?,job_title=?,phone=?,address=?,email=?,web=? WHERE id=?"); $upd_c->execute([$name,$company,$comment,$job_title,$phone,$address,$email,$web,$id]);
		}
		if(!empty($id)){
			if(!empty($_REQUEST["edit_page22"]) && $_REQUEST["edit_page22"]=="yes" && !empty($domain2)){

				$domain2 = sanitize_domain($domain2);
				//if(empty($id)){
					$ins_d = $pdo->prepare("INSERT INTO adm_domains (domain,clientID,dateCreated) VALUES (?,?,datetime('now'))");
					$ins_d->execute([$domain2, $id]);
					$id2 = $pdo->lastInsertId();
					$msg2 = '<div class="alert alert-success alert-dismissible">
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
								<h5><i class="icon fas fa-check"></i> '.$lang['DOMAIN_SUCCESS_ADDED'].'</h5>
							</div>';
				
				$upd_d = $pdo->prepare("UPDATE adm_domains SET comment=?,domain=?,registrar=?,renewalDate=?,registrationDate=?,renew_link=?,clientID=? WHERE id=?"); $upd_d->execute([$comment2,$domain2,$registrar2,$renewalDate2,$registrationDate2,$renew_link2,$id,$id2]);
				
				
				if($autoWhois2=="yes" && !empty($domain2)){
					$autoWArr = getWhoisData($domain2); //new function - auto-whois checker, created by me, using http://www.phpwhois.com/ class
						if($autoWArr[0]=="yes"){ 

							//domain registered
							$registrationDate2=$autoWArr[1];
							$renewalDate2=$autoWArr[2];
							$registrar2=$autoWArr[3];
							$whoisreply=$autoWArr[4];
							$fail = false;
							if(!is_numeric(str_replace("-","",$registrationDate2))){ $registrationDate2='0000-00-00'; $msg2 .= " Domain whois failed for registration date."; $fail=true; } 
							if(!is_numeric(str_replace("-","",$renewalDate2))){ $renewalDate2='0000-00-00';  $msg2 .= '<div class="alert alert-warning alert-dismissible">
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
									<h5><i class="icon fas fa-exclamation-triangle"></i> '.$lang['DOMAIN_RENEWAL_DATE_FAIL'].'</h5>
								</div>'; $fail=true; } 						   
							

							$upd_w = $pdo->prepare("UPDATE adm_domains SET renewalDate=?,registrationDate=?,registrar=?,whoisreply=? WHERE id=?");
							$upd_w->execute([$renewalDate2, $registrationDate2, $registrar2, $whoisreply, $id2]);

							// executed via $upd_w above
							if(!$fail){ $msg2 .= '<div class="alert alert-success alert-dismissible">
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
								<h5><i class="icon fas fa-check"></i> '.$lang['DOMAIN_WHOIS_SUCCESS'].'</h5>
							</div>'; }
							
						} else {
							$upd_w2 = $pdo->prepare("UPDATE adm_domains SET whoisreply=? WHERE id=?");
							$upd_w2->execute([$autoWArr[4], $id2]);
							$msg3 .= '<div class="alert alert-warning alert-dismissible">
										<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
										<h5><i class="icon fas fa-exclamation-triangle"></i> '.$lang['DOMAIN_WHOIS_FAIL_REG'].'</h5>
									</div>';
						}
				}
			}
			
			$filter = "";  //default filter variable. getting rid of undefined variable exception.
			$bgClass = "even"; // default first row highlighting CSS class
			$files_table = ""; //var with php generated html table.
			//sorting

			$_SESSION["adm_domains.orderBy"]= (!empty($_REQUEST["orderBy"]))?$_REQUEST["orderBy"]:(!empty($_SESSION["adm_domains.orderBy"])?$_SESSION["adm_domains.orderBy"]:"renewalDate");
			$_SESSION["adm_domains.direction"]= (!empty($_REQUEST["direction"]))?$_REQUEST["direction"]:(!empty($_SESSION["adm_domains.direction"])?$_SESSION["adm_domains.direction"]:'ASC');

			$orby = $_SESSION['adm_domains.orderBy'];
			$didi = $_SESSION['adm_domains.direction'];

			$filter .= " WHERE clientID='".$id."' ORDER BY $orby $didi";
			//"delete selected files" action processing.
			if (!empty($_REQUEST["files_delete"]) && $_REQUEST["files_delete"] == "yes" && isset($_POST["filesToDel"]) && is_array($_POST["filesToDel"])) {
				$del_ids2 = array_filter(array_map("intval", $_POST["filesToDel"]));
				if (!empty($del_ids2)) {
					$ph2 = implode(",", array_fill(0, count($del_ids2), "?"));
					$pdo->prepare("DELETE FROM adm_domains WHERE id IN ($ph2)")->execute(array_values($del_ids2));
					$msg1 = '<div class="alert alert-success alert-dismissible">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
							<h5><i class="icon fas fa-check"></i> '.$lang['SELECTED_DOMAINS_DELETED'].'</h5>
						</div>';
				}
			}
		
			//PAGES TABLE  GENERATION TO SHOW IN HTML BELOW
			$dom_stmt = $pdo->prepare("SELECT * FROM adm_domains $filter"); $dom_stmt->execute(); $dom_rows=$dom_stmt->fetchAll();
			if(count($dom_rows)>0){
				foreach($dom_rows as $rr){
						  
					//PERMISSION CHECK - for showing EDIT FILE icon.
				   
					$editable='<a href="domains-edit.php?id='.$rr["id"].'"><i class="fas fa-edit" title="'.$lang['EDIT'].'"></i></a>';  

					//$bgClass=($bgClass=="even"?"odd":"even");

					$files_table .= '<tr>';
					//$files_table .= "<tr class=\"".$bgClass."\">";
					//$files_table .= "";
					$files_table .= '<td><input name="filesToDel[]" type="checkbox" value="'.$rr["id"].'" /></td>';
					$files_table .= '<td>'.$rr["domain"].'</td>';
					$files_table .= '<td>'.getClientName($rr["clientID"]).'</td>';
					$files_table .= '<td>'.($rr["registrationDate"]!="0000-00-00"?date("d M Y",strtotime($rr["registrationDate"])):"------").'</td>';
					$files_table .= '<td>'.($rr["renewalDate"]!="0000-00-00"?date("d M Y",strtotime($rr["renewalDate"])):"------").'</td>';
					$files_table .= '<td>'.$rr["registrar"].'</td>';
					$files_table .= '<td>'.$editable.'</td></tr>';

				} // end of all files from db query (end of while loop)
		
				//show button to complete file deletion if proper permissions.

				$files_table .='<tr><td><button type="submit" name="delete_files" class="btn btn-block btn-danger del-confirm"><i class="fas fa-trash-alt" title="'.$lang['DELETE_SELECTED'].'"></i></button></td></tr>';
				
			} else { 
				//0 files found in database. ( end of IF mysqli_num_rows > 0 )
				$files_table .='<tr><td colspan="7"> '.$lang['0_DOMAINS_IN_DB'].'</td></tr>';
			} 
		  
			$direction=($_SESSION["adm_domains.direction"]=='DESC')?('ASC'):('DESC');
		}
		//select editable user's info and show it for editor.
		$cli_sel = $pdo->prepare("SELECT id,name,company,comment,job_title,phone,address,email,web FROM adm_clients WHERE id=?"); $cli_sel->execute([$id]);
		if($row = $cli_sel->fetch()){
		  foreach($row as $key =>$value){ $$key=$value;}
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
		<!-- daterange picker -->
		<link rel="stylesheet" href="assets/js/daterangepicker/daterangepicker.css">
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
								<a href="clients-add.php" class="nav-link active"> <i class="nav-icon fas fa-user-plus"></i>
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
								<h1 class="m-0 text-primary"><i class="fas fa-user-edit"></i> <?php echo $lang['ADD_EDIT_CLIENT']; ?></h1> 
							</div>
							<!-- /.col -->
						</div>
						<!-- /.row -->
					</div>
					<!-- /.container-fluid -->
				</div>
				
				<section class="content">
					
					<div class="col-md-6">
						<?php echo $msg; ?>
						<?php echo $msg1; ?>
						<?php echo $msg2; ?>
					</div>
					
					<div class="col-lg-12">
						<div class="card card-primary card-outline">
							<div class="card-body">
								
								<form class="col-lg-8" action="clients-add.php" enctype="multipart/form-data" method="post" name="ff1">
									<div class="card-body">
										<div class="row">
											<div class="form-group col-sm-6">
												<label><?php echo $lang['CONTACT_NAME'];?>:</label>
												<div class="input-group">
													<div class="input-group-prepend">
														<span class="input-group-text"><i class="fas fa-address-card text-primary"></i></span>
													</div>
													<input type="text" class="form-control" name="name" id="name" value="<?php echo $name?>" placeholder="">
												</div>
											</div>
											<div class="form-group col-sm-6">
												<label><?php echo $lang['JOB_TITLE'];?>:</label>
												<div class="input-group">
													<div class="input-group-prepend">
														<span class="input-group-text"><i class="far fa-address-card text-primary"></i></span>
													</div>
													<input type="text" class="form-control" name="job_title" id="job_title" value="<?php echo $job_title?>">
												</div>
											</div>
										</div>
										
										<div class="row">
											<div class="form-group col-sm-6">
												<label><?php echo $lang['CLIENT_COMPANY'];?>:</label>
												<div class="input-group">
													<div class="input-group-prepend">
														<span class="input-group-text"><i class="fas fa-building text-primary"></i></span>
													</div>
													<input type="text" class="form-control" name="company" id="company" value="<?php echo $company?>">
												</div>
											</div>
											<div class="form-group col-sm-6">
												<label><?php echo $lang['WEBSITE'];?>:</label>
												<div class="input-group">
													<div class="input-group-prepend">
														<span class="input-group-text"><i class="fas fa-globe text-primary"></i></span>
													</div>
													<input type="text" class="form-control" name="web" id="web" value="<?php echo $web?>">
												</div>
											</div>
										</div>
										
										<div class="row">
											<div class="form-group col-sm-6">
												<label><?php echo $lang['CONTACT_EMAIL'];?>:</label>
												<div class="input-group">
													<div class="input-group-prepend">
														<span class="input-group-text"><i class="fas fa-envelope text-primary"></i></span>
													</div>
													<input type="text" class="form-control" name="email" id="email" value="<?php echo $email?>">
												</div>
											</div>
											<div class="form-group col-sm-6">
												<label><?php echo $lang['PHONE_NUMBER'];?>:</label>
												<div class="input-group">
													<div class="input-group-prepend">
														<span class="input-group-text"><i class="fas fa-phone text-primary"></i></span>
													</div>
													<input type="text" class="form-control" name="phone" id="phone" value="<?php echo $phone?>">
												</div>
											</div>
										</div>
										
										<div class="row">
											<div class="form-group col-sm-12">
												<label><?php echo $lang['COMPANY_ADDRESS'];?>:</label>
												<div class="input-group">
													<div class="input-group-prepend">
														<span class="input-group-text"><i class="fas fa-map-marked-alt text-primary"></i></span>
													</div>
													<input type="text" class="form-control" name="address" id="address" value="<?php echo $address?>">
												</div>
											</div>
										</div>
									
										<div class="row">
											<div class="col-sm-12">
												<div class="form-group">
													<label><i class="fas fa-file-alt text-primary"></i> <?php echo $lang['OTHER'];?>:</label>
													<textarea class="form-control" name="comment" id="comment"><?php echo $comment?></textarea>
												</div>
											</div>
										</div>
									</div>
									<!-- /.card-body -->

									<div class="card-footer">
										<button type="submit" name="create" id="create" class="btn btn-primary float-right"><?php echo $lang['SAVE'];?></button>
										<input value="yes" name="edit_page" type="hidden" />
										<input value="<?php echo $id;?>" name="id" type="hidden" />
									</div>
								</form>
								
							</div>
						</div>
					</div>
				
				</section>
				
					<?php if(!empty($id)){ ?>
					
				<section class="content">
					
					<div class="col-md-6">
						<?php echo $msg1; ?>
						<?php echo $msg2; ?>
						<?php echo $msg3; ?>
					</div>
					
					<div class="col-lg-12">
						<div class="card card-info card-outline card-tabs">
						
							<div class="card-header p-0 pt-1 border-bottom-0">
								<ul class="nav nav-tabs" id="custom-tabs-two-tab" role="tablist">
									<li class="nav-item">
										<a class="nav-link active" id="custom-tabs-two-home-tab" data-toggle="pill" href="#custom-tabs-two-home" role="tab" aria-controls="custom-tabs-two-home" aria-selected="true"><strong><i class="nav-icon fas fa-globe"></i> <?php echo $lang['DOMAINS'];?> </strong></a>
									</li>
									<li class="nav-item">
										<a class="nav-link" id="custom-tabs-two-profile-tab" data-toggle="pill" href="#custom-tabs-two-profile" role="tab" aria-controls="custom-tabs-two-profile" aria-selected="false"><strong><i class="fas fa-plus-circle"></i> <?php echo $lang['ADD_DOMAIN'];?> </strong></a>
									</li>
								</ul>
							</div>
							
							<div class="card-body">
								<div class="tab-content" id="custom-tabs-two-tabContent">
								
									<div class="tab-pane fade show active" id="custom-tabs-two-home" role="tabpanel" aria-labelledby="custom-tabs-two-home-tab">
										
										<form enctype="multipart/form-data" action="clients-add.php" method="post" name="ff2">
											<input type="hidden" value="yes" name="files_delete" />
											<input value="yes" name="edit_page" type="hidden" />
											<input value="<?php echo $id;?>" name="id" type="hidden" />
											
											<table class="table table-striped">
												<thead>
													<tr>
														<th>&nbsp;</th>
							
														<th><strong><a onclick="document.ff2.orderBy.value='domain'; document.ff2.direction.value='<?php echo $direction?>'; document.ff2.submit();" style="cursor:pointer;"><?php echo $lang['DOMAIN_NAME'];?></a>&nbsp;<?php if($_SESSION["adm_domains.orderBy"]=="domain"){ ?><img src="assets/img/<?php echo strtolower($direction)?>.png" /><?php } ?></strong></th>

														<th><strong><a onclick="document.ff2.orderBy.value='clientID'; document.ff2.direction.value='<?php echo $direction?>'; document.ff2.submit();" style="cursor:pointer;"><?php echo $lang['CLIENT_NAME'];?></a>&nbsp;<?php if($_SESSION["adm_domains.orderBy"]=="clientID"){ ?><img src="assets/img/<?php echo strtolower($direction)?>.png" /><?php } ?></strong></th>

														<th><strong><a onclick="document.ff2.orderBy.value='registrationDate'; document.ff2.direction.value='<?php echo $direction?>'; document.ff2.submit();" style="cursor:pointer;"><?php echo $lang['REGISTRATION_DATE'];?></a>&nbsp;<?php if($_SESSION["adm_domains.orderBy"]=="registrationDate"){ ?><img src="assets/img/<?php echo strtolower($direction)?>.png" /><?php } ?></strong></th> 
												
														<th><strong><a onclick= "document.ff2.orderBy.value='renewalDate'; document.ff2.direction.value='<?php echo $direction?>'; document.ff2.submit();" style="cursor:pointer;"><?php echo $lang['RENEWAL_DATE'];?></a>&nbsp;<?php if($_SESSION["adm_domains.orderBy"]=="renewalDate"){ ?><img src="assets/img/<?php echo strtolower($direction)?>.png" /><?php } ?></strong></th>
												
														<th><strong><a onclick="document.ff2.orderBy.value='registrar'; document.ff2.direction.value='<?php echo $direction?>'; document.ff2.submit();" style="cursor:pointer;"><?php echo $lang['REGISTRAR'];?></a>&nbsp;<?php if($_SESSION["adm_domains.orderBy"]=="registrar"){ ?><img src="assets/img/<?php echo strtolower($direction)?>.png" /><?php } ?></strong></th>
													
														<th><?php echo $lang['EDIT'];?></th>
													</tr>
												</thead>
												
												<tbody>
													<?php echo $files_table; ?>
												</tbody>
												
											</table>
											<input type="hidden" name="orderBy" value="<?php $_SESSION["adm_domains.orderBy"] ?>">
											<input type="hidden" name="direction" value="<?php $_SESSION["adm_domains.direction"] ?>">
										</form>
										
									</div>
									
									<div class="tab-pane fade" id="custom-tabs-two-profile" role="tabpanel" aria-labelledby="custom-tabs-two-profile-tab">
										<form class="col-sm-8" action="clients-add.php" enctype="multipart/form-data" method="post" name="ff11">
										
											<input type="hidden" value="<?php echo $id?>" name="clientID2" />
											
											<div class="row">
												<div class="form-group col-sm-12">
													<label><?php echo $lang['DOMAIN_NAME'];?>:</label>
													<div class="input-group">
														<div class="input-group-prepend">
															<span class="input-group-text"><i class="fas fa-globe text-primary"></i></span>
														</div>
														<input class="form-control" type="text" name="domain2" id="domain" value="" /> 
													</div>
												</div>
											</div>
											
											<div class="row">
												<div class="form-group col-sm-6">
													<label><?php echo $lang['REGISTRAR'];?>:</label>
													<div class="input-group">
														<div class="input-group-prepend">
															<span class="input-group-text"><i class="fas fa-file-signature text-primary"></i></span>
														</div>
														<input class="form-control" type="text" name="registrar2" id="registrar" value="" />
													</div>
												</div>
												<div class="form-group col-sm-6">
													<label><?php echo $lang['RENEWAL_URL'];?>:</label>
													<div class="input-group">
														<div class="input-group-prepend">
															<span class="input-group-text"><i class="fas fa-file-import text-primary"></i></span>
														</div>
														<input class="form-control" type="text" name="renew_link2" />
													</div>
												</div>
											</div>
											
											<div class="row">
												<div class="form-group col-sm-6">
													<label><?php echo $lang['REGISTRATION_DATE'];?>:</label>
													<div class="input-group">
														<div class="input-group-prepend">
															<span class="input-group-text"><i class="fas fa-calendar text-primary"></i></span>
														</div>
														<input class="form-control" type="text" name="registrationDate2"/> 
													</div>
												</div>
												<div class="form-group col-sm-6">
													<label><?php echo $lang['RENEWAL_DATE'];?>:</label>
													<div class="input-group">
														<div class="input-group-prepend">
															<span class="input-group-text"><i class="fas fa-calendar-check text-primary"></i></span>
														</div>
														<input class="form-control" type="text" name="renewalDate2"/>
													</div>
												</div>
											</div>
											
											<div class="row">
												<div class="col-sm-12">
													<div class="form-group">
														<label><i class="fas fa-file-alt text-primary"></i> <?php echo $lang['OTHER'];?>:</label>
														<textarea class="form-control" name="comment2" id="comment"></textarea>
													</div>
												</div>
											</div>
											
											<div class="row">
												<div class="col-sm-12">
													<div class="form-group">
														<label><i class="fas fa-receipt text-primary"></i> <?php echo $lang['GET_AUTO_WHOIS'];?></label>
														<div class="col-sm-2 offset-md-1">
															<input type="checkbox" name="autoWhois2" id="autoWhois"  value="yes"/> <?php echo $lang['YES'];?>.
														</div>
													</div>
												</div>
											</div>
										
											<!-- /.card-body -->
											<div class="card-footer">
												<button type="submit" name="create" id="create" class="btn btn-info"><?php echo $lang['SAVE'];?></button>
												<input value="yes" name="edit_page22" type="hidden" />
												<input value="<?php echo $id;?>" name="id" type="hidden" />
											</div>
											<!-- /.card-footer -->
												
										</form>
										
									</div>
							
								</div>
							</div>
							
						</div>
					</div>
					
					<?php } ?>
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
		
		<?php if(!empty($id)){?>
			<!-- InputMask -->
			<script src="assets/js/moment/moment.min.js"></script>
			<!-- date-range-picker -->
			<script src="assets/js/daterangepicker/daterangepicker.js"></script>
			<script type="text/javascript">
				$(function() {
					$('input[name="registrationDate2"]').daterangepicker({
						locale: { format: 'YYYY-MM-DD' },
						singleDatePicker: true,
						showDropdowns: true,
						minYear: (1990),
						maxYear: (2030),
						drops: 'up', //('down'/'up'/'auto')
						autoApply: true
					});
					$('input[name="renewalDate2"]').daterangepicker({
						locale: { format: 'YYYY-MM-DD' },
						singleDatePicker: true,
						showDropdowns: true,
						minYear: (1990),
						maxYear: (2030),
						drops: 'up', //('down'/'up'/'auto')
						autoApply: true
					});
				});
			</script>
		<?php } ?>
		
		<script src="edited/del-confirm.js"></script>

	</body>

</html>
<?php } ?>