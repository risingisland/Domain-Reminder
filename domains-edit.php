<?php

	session_start();
    require_once("includes/dbconnect.php"); //Load the settings
	require_once("includes/functions.php"); //Load the functions
	require_once("includes/languages.php"); //Load the langs
	
	$files_table = "";
	$msg 	= "";
	$msg2 	= "";
	$msg3 	= "";
	
	/* test -test*/
	if (!isset($_SESSION["username"]) && isset($_COOKIE["username"])) {
		$_SESSION["username"] = $_COOKIE["username"];
	}
	if (!isset($_SESSION["username"])) {
		header("Location: index.php");
	exit();
	
	} else {
	
	//show page only if admin access level
	
	//request all neccessary variables for user update action.
	$id					= (!empty($_REQUEST["id"]))?strip_tags(str_replace("'","`",$_REQUEST["id"])):'';
	$domain				= (!empty($_REQUEST["domain"]))?strip_tags(str_replace("'","`",$_REQUEST["domain"])):'';
	if(!empty($domain)){ $domain = str_replace("https://","",$domain); $domain = str_replace("http://","",$domain); $domain = str_replace("www.","",$domain);  $domain = trim($domain,"/"); }
	$registrar			= (!empty($_REQUEST["registrar"]))?strip_tags(str_replace("'","`",$_REQUEST["registrar"])):'';
	$registrarwww		= (!empty($_REQUEST["registrarwww"]))?strip_tags(str_replace("'","`",$_REQUEST["registrarwww"])):'';
	$renewalDate 		= (!empty($_REQUEST["renewalDate"]))?strip_tags(str_replace("'","`",$_REQUEST["renewalDate"])):'';
	$registrationDate 	= (!empty($_REQUEST["registrationDate"]))?strip_tags(str_replace("'","`",$_REQUEST["registrationDate"])):'';
	$clientID			= (!empty($_REQUEST["clientID"]))?strip_tags(str_replace("'","`",$_REQUEST["clientID"])):'';
	$comment			= (!empty($_REQUEST["comment"]))?str_replace("'","`",$_REQUEST["comment"]):'';
	$autoWhois			= (!empty($_REQUEST["autoWhois"]))?strip_tags(str_replace("'","`",$_REQUEST["autoWhois"])):'';
	
	$comment2			= (!empty($_REQUEST["comment2"]))?strip_tags(str_replace("'","`",$_REQUEST["comment2"])):'';
	$username2			= (!empty($_REQUEST["username2"]))?strip_tags(str_replace("'","`",$_REQUEST["username2"])):'';
	$password2			= (!empty($_REQUEST["password2"]))?strip_tags(str_replace("'","`",$_REQUEST["password2"])):'';
	$type2				= (!empty($_REQUEST["type2"]))?str_replace("'","`",$_REQUEST["type2"]):'';
	$renew_link			= (!empty($_REQUEST["renew_link"]))?strip_tags(str_replace("'","`",$_REQUEST["renew_link"])):'';
	$host2				= (!empty($_REQUEST["host2"]))?strip_tags(str_replace("'","`",$_REQUEST["host2"])):'';
	$database2 			= (!empty($_REQUEST["database2"]))?strip_tags(str_replace("'","`",$_REQUEST["database2"])):'';
	
	$domainName			= (!empty($_REQUEST["domainName"]))?strip_tags(str_replace("'","`",$_REQUEST["domainName"])):'';
	$domainID			= (!empty($_REQUEST["domainID"]))?str_replace("'","`",$_REQUEST["domainID"]):'';
	$whoisreply			= (!empty($_REQUEST["whoisreply"]))?str_replace("'","`",$_REQUEST["whoisreply"]):'';
	//process data deletion
	if(!empty($_REQUEST["files_delete"]) && $_REQUEST["files_delete"]=="yes"){
		$filesToDel = (!empty($_REQUEST["filesToDel"]))?$_REQUEST["filesToDel"]:array();
		if(is_array($_POST['filesToDel'])){ 
			if(join(",", $_POST['filesToDel'])!='') {
				//delete file from database		
				$sql 	= "DELETE FROM adm_data WHERE id IN ('".join("','", $_POST['filesToDel'])."')";
				$result = mysqli_query($mysqli,$sql) or die("oopsy, error when tryin to delete data 1");
				$msg2 	= '<div class="alert alert-success alert-dismissible">
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
								<h5><i class="icon fas fa-check"></i> '.$lang['SELECTED_DATA_DELETED'].'</h5>
							</div> ';
			 }
		} 
	}
	
	//add new data "edit page2" action processing.
	if(!empty($_REQUEST["edit_page22"]) && $_REQUEST["edit_page22"]=="yes" && !empty($id)  && !empty($domainName)  && !empty($domainID)  ){
		
				$sql="INSERT INTO adm_data (type,aa,bb,cc,dd,ee,ff) VALUES ('".$type2."','".xorEncrypt($username2,$domainName.$id.$clientID)."','".xorEncrypt($password2,$clientID.$domainName.$id)."','".xorEncrypt($comment2,$clientID.$domainName.$id)."','".xorEncrypt($domainName,strtoupper($domainName))."','".xorEncrypt($host2,$clientID.$domainName.$id)."','".xorEncrypt($database2,$clientID.$domainName.$id)."')";
				$result=mysqli_query($mysqli,$sql) or die("oopsy, error occured when tryin to create new domain. 2");
				//$id = mysqli_insert_id($mysqli);
				$msg3 = '<div class="alert alert-success alert-dismissible">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
							<h5><i class="icon fas fa-check"></i> '.$lang['DATA_ADDED'].'</h5>
						</div> ';
		
	}
	
	//"edit page" action processing.
	if($renewalDate=="YYYY-MM-DD" || $renewalDate==''){ $renewalDate="0000-00-00"; }
	if($registrationDate=="YYYY-MM-DD" || $registrationDate==''){ $registrationDate="0000-00-00"; }
	if(!empty($_REQUEST["edit_page"]) && $_REQUEST["edit_page"]=="yes" && !empty($domain)  && !empty($clientID) ){
						
			if(empty($id)){
				$sql="INSERT INTO adm_domains (domain,clientID,dateCreated) VALUES ('".$domain."','".$clientID."',NOW())";
				$result=mysqli_query($mysqli,$sql) or die("oopsy, error occured when tryin to create new domain. 3");
				$id = mysqli_insert_id($mysqli);
				$msg = '<div class="alert alert-success alert-dismissible">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
							<h5><i class="icon fas fa-check"></i> '.$lang['DOMAIN_SUCCESS_ADDED'].'</h5>
						</div> ';
			}
			$msg .= '<div class="alert alert-success alert-dismissible">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
						<h5><i class="icon fas fa-check"></i> '.$lang['DOMAIN_SUCCESS_UPDATED'].'</h5>
					</div> ';
			$sql="UPDATE adm_domains SET comment='".$comment."',domain='".$domain."',registrar='".$registrar."',renewalDate='".$renewalDate."',registrationDate='".$registrationDate."', clientID='".$clientID."', renew_link='".$renew_link."' WHERE id='".$id."'";
			$result=mysqli_query($mysqli,$sql) or die("oopsy, error occured when tryin to update page.");
			
			if($autoWhois=="yes" && !empty($domain)){
				$autoWArr = getWhoisData($domain);//new function - auto-whois checker, created by me, using http://www.phpwhois.com/ class
					if($autoWArr[0]=="yes"){ 
					//var_dump($autoWArr);
						//domain registered
						//dump($autoWArr);
						$registrationDate=$autoWArr[1];
						$renewalDate=$autoWArr[2];
						$registrar=$autoWArr[3];
						$whoisreply=$autoWArr[4];
						$fail = false;

						if(!is_numeric(str_replace("-","",$registrationDate))){ $registrationDate='0000-00-00'; $msg .= '<div class="alert alert-warning alert-dismissible">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
							<h5><i class="icon fas fa-exclamation-triangle"></i> '.$lang['DOMAIN_WHOIS_FAIL_REG_DATE'].'</h5>
						</div> '; $fail=true; } 
						if(!is_numeric(str_replace("-","",$renewalDate))){ $renewalDate='0000-00-00';  $msg .= '<div class="alert alert-warning alert-dismissible">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
							<h5><i class="icon fas fa-exclamation-triangle"></i>  '.$lang['DOMAIN_RENEWAL_DATE_FAIL'].'</h5>
						</div> '; $fail=true; } 						   
						
						$sql="UPDATE adm_domains SET renewalDate='".$renewalDate."',registrationDate='".$registrationDate."',registrar='".addslashes($registrar)."',whoisreply='".addslashes($whoisreply)."' WHERE id='".$id."'";

						$result=mysqli_query($mysqli,$sql) or die("oopsy, error occured when tryin to update domain. 4");
						if(!$fail){ $msg .= '<div class="alert alert-success alert-dismissible">
												<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
												<h5><i class="icon fas fa-check"></i> '.$lang['DOMAIN_WHOIS_SUCCESS'].'</h5>
											</div> '; }
						
					} else {
						$sql="UPDATE adm_domains SET whoisreply='".addslashes($autoWArr[4])."' WHERE id='".$id."'";
						$result=mysqli_query($mysqli,$sql) or die("oopsy, error occured when tryin to update domain. ");
						$msg .= '<div class="alert alert-warning alert-dismissible">
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
									<h5><i class="icon fas fa-exclamation-triangle"></i> '.$lang['DOMAIN_WHOIS_FAIL_REG'].'</h5>
								</div> ';
					}
			}
	}
	
	//select editable user's info and show it for editor.
	$sSQL = "SELECT id,comment,domain,registrar,renewalDate,registrationDate,clientID,whoisreply,renew_link FROM adm_domains WHERE id='".$id."'";
	$result = mysqli_query($mysqli,$sSQL) or die("err: " . mysqli_error($mysqli).$sSQL);
	if($row = mysqli_fetch_assoc($result)){
	  foreach($row as $key =>$value){ $$key=$value;}
	}
	mysqli_free_result($result);
		$bgClass="";
	if(!empty($id)){
	$jks=xorEncrypt($domain,strtoupper($domain));
	//create $files_table for showing data.
	  $sql="SELECT * FROM adm_data WHERE dd='".$jks."'";
	  $result=mysqli_query($mysqli,$sql) or die("error getting data");
	  if(mysqli_num_rows($result)>0){
		  while($rr=mysqli_fetch_assoc($result)){
			 		  
			    //PERMISSION CHECK - for showing EDIT FILE icon.
	  		 $bgClass=($bgClass=="even"?"odd":"even");
			 switch($rr["type"]){
			 	case "1": $tip = "FTP Login"; break;
				case "2": $tip = "Database Info"; break;
				case "3": $tip = "Webmail"; break;
				case "4": $tip = "Other"; break;
				case "5": $tip = "Control Panel"; break;
				default: $tip  = "FTP Login"; break;   
			 }
			 $files_table .= '<tr class="'.$bgClass.'">';
			 //$files_table .= "";
			 $files_table .= '<td style="text-align:center;"><input name="filesToDel[]" type="checkbox" value="'.$rr["id"].'" /></td>';
			 $files_table .= '<td>'.$tip.'</a></td>';
			 $files_table .= '<td>'.xorDecrypt($rr["aa"],$domain.$id.$clientID).'</td>';
			 $files_table .= '<td>'.xorDecrypt($rr["bb"],$clientID.$domain.$id).'</td>';
			 $files_table .= '<td>'.xorDecrypt($rr["ee"],$clientID.$domain.$id).'</td>';
			 $files_table .= '<td>'.xorDecrypt($rr["ff"],$clientID.$domain.$id).'</td>';
			 $files_table .= '<td>'.xorDecrypt($rr["cc"],$clientID.$domain.$id).'</td>';
			 //$files_table .= "<td>&nbsp;</td></tr>";

	     } // end of all files from db query (end of while loop)
	
		   //show button to complete file deletion if proper permissions.
		  
			$files_table .= '<tr><td> <button type="submit" name="delete_files" value="Delete Selected" class="btn btn-block btn-danger"><i class="fas fa-trash-alt" title="'.$lang['DELETE_SELECTED'].'"></i></button> </td></tr>';
		   
	   
	  } else { 
		//0 files found in database. ( end of IF mysqli_num_rows > 0 )
		$files_table .= '<tr><td colspan="7">'.$lang['0_RECORDS_IN_DB'].'</td></tr>';
	  } 
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
							<i class="fas fa-cogs mr-2  text-info"></i> <?php echo $lang['SETTINGS'];?>
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
								<a href="domains.php" class="nav-link active"> <i class="nav-icon fas fa-globe"></i>
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
								<h1 class="m-0 text-primary"><i class="fas fa-globe"></i> <?php echo $lang['EDIT_DOMAIN'];?></h1> 
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
						<?php echo $msg2; ?>
						<?php echo $msg3; ?>
					</div>
					
					<div class="col-lg-12">
						<div class="card card-primary card-outline">
							<div class="card-body">
								
								<form class="col-sm-8" action="domains-edit.php" enctype="multipart/form-data" method="post" name="ff11">
								
									<div class="row">
										<div class="col-sm-6">
											<div class="form-group">
												<label><i class="fas fa-user-check text-primary"></i> <?php echo $lang['CLIENT_NAME'];?>:</label>
												<select name="clientID" class="form-control">
													<option value=""> <?php echo $lang['PLEASE_SELECT'];?></option>
													<?php $q="SELECT * FROM adm_clients ORDER BY name ASC";
														$res=mysqli_query($mysqli,$q);
														if(mysqli_num_rows($res)>0){
															while($r=mysqli_fetch_assoc($res)){ ?><option value="<?php echo $r["id"]?>" <?php echo $clientID==$r["id"]?"selected":""?>><?php echo $r["company"]?> (<?php echo $r["name"]?>)</option><?php }
														} ?>
												</select>
											</div>
										</div>
										<div class="form-group col-sm-6">
											<label><?php echo $lang['DOMAIN_NAME'];?>:</label>
											<div class="input-group">
												<div class="input-group-prepend">
													<span class="input-group-text"><i class="fas fa-globe text-primary"></i></span>
												</div>
												<input class="form-control" type="text" name="domain" id="domain" value="<?php echo $domain?>" />
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
												<input class="form-control" type="text" name="registrar" id="registrar" value="<?php echo $registrar?>" />
											</div>
										</div>
										<div class="form-group col-sm-6">
											<label><?php echo $lang['RENEWAL_URL'];?>:</label>
											<div class="input-group">
												<div class="input-group-prepend">
													<span class="input-group-text"><i class="fas fa-file-import text-primary"></i></span>
												</div>
												<input class="form-control" type="text" name="renew_link" value="<?php echo $renew_link?>"   id="renew_link" />
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
												<input class="form-control" type="text" name="registrationDate" value="<?php echo empty($registrationDate)?"YYYY-MM-DD":$registrationDate?>" />
											</div>
										</div>
										<div class="form-group col-sm-6">
											<label><?php echo $lang['RENEWAL_DATE'];?>:</label>
											<div class="input-group">
												<div class="input-group-prepend">
													<span class="input-group-text"><i class="fas fa-calendar-check text-primary"></i></span>
												</div>
												<input class="form-control" type="text" name="renewalDate" value="<?php echo empty($renewalDate)?"YYYY-MM-DD":$renewalDate?>" /> 
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
									
									<div class="row">
										<div class="col-sm-12">
											<div class="form-group">
												<label><i class="fas fa-receipt text-primary"></i> <?php echo $lang['GET_AUTO_WHOIS'];?></label>
												<div class="col-sm-2 offset-md-1">
													<input type="checkbox" name="autoWhois" id="autoWhois"  value="yes"/> <?php echo $lang['YES'];?>.
												</div>
											</div>
										</div>
									</div>
								
									<!-- /.card-body -->
									<div class="card-footer">
										<button type="submit" value="Submit" name="create" id="create" class="btn btn-primary float-right"><?php echo $lang['UPDATE'];?></button>
										<input value="yes" name="edit_page" type="hidden" />
										<input value="<?php echo $id;?>" name="id" type="hidden" />
									</div>
									<!-- /.card-footer -->
									<hr>
									<div class="form-group">
										<?php
										$show_debug = getSetting('1','show_debug');
										if($show_debug=="1"){  ?>
											<label class="text-info"><?php echo $lang['WHOIS_REPLY'];?>:</label>
											<textarea class="form-control" style="min-height:300px;" name="whoisreply" id="whoisreply" disabled=""><?php echo stripslashes($whoisreply)?></textarea>
										<?php } ?>
									</div>
								
								</form>
								
							</div>
						</div>
					</div>
				
				</section>
				
				<?php if(!empty($id)){ ?>
				
				<?php
				$show_domdata = getSetting('1','show_domdata');
				if($show_domdata=="1"){  ?>
				
				<section class="content">
					
					<div class="col-md-6">
						<?php echo $msg2; ?>
						<?php echo $msg3; ?>
					</div>
					
					<div class="col-lg-12">
						<div class="card card-info card-outline card-tabs">
						
							<div class="card-header border-bottom-0">
								<h3 class="text-info"><?php echo $lang['DOMAIN_DATA'];?></h3>
							</div>
							
							<div class="card-body">
								<form enctype="multipart/form-data" action="domains-edit.php" method="post" name="ff2">
									<input type="hidden" value="yes" name="files_delete" />
									<input value="yes" name="edit_page" type="hidden" />
									<input value="<?php echo $id;?>" name="id" type="hidden" />
									
									<table class="table table-striped">
										<thead>
											<tr>
												<th>&nbsp;</th>
												<th><?php echo $lang['TYPE'];?></th>
												<th><?php echo $lang['USERNAME'];?></th>
												<th><?php echo $lang['PASSWORD'];?></th>
												<th><?php echo $lang['HOST_NAME'];?></th> 
												<th><?php echo $lang['DATABASE_NAME'];?></th>                         
												<th><?php echo $lang['COMMENT'];?></th>
											</tr>
										</thead>
										
										<tbody>
											<tr>
											<?php echo $files_table; ?>
										</tbody>
									</table>
									
								</form>
								
								<hr>
								
								<h3 class="text-info"><?php echo $lang['ADD_DATA'];?></h3>
								<form action="domains-edit.php" enctype="multipart/form-data" method="post" name="ff11">
								
									<div class="col-sm-6">
										<div class="form-group">
											<label for="inputEmail3" class="col-form-label"><i class="fas fa-table text-primary"></i> <?php echo $lang['DATA_TYPE'];?>:</label>
											<div class="ccol-sm-9">
												<select class="form-control" name="type2" onchange="checkType(this.value)">
													<option value="1" selected="selected"><?php echo $lang['FTP_LOGIN'];?></option>
													<option value="2"><?php echo $lang['DATABASE_INFO'];?></option>
													<option value="3"><?php echo $lang['WEBMAIL'];?></option>
													<option value="5"><?php echo $lang['CONTROL_PANEL'];?> </option>
													<option value="4"><?php echo $lang['OTHER'];?></option>
												</select>
											</div>
										</div>
									</div>
									
									<div class="form-group  row col-sm-6">
										<label><?php echo $lang['USERNAME'];?>:</label>
										<div class="input-group">
											<div class="input-group-prepend">
												<span class="input-group-text"><i class="fas fa-user text-primary"></i></span>
											</div>
											<input type="text" class="form-control" name="username2" /> 
										</div>
									</div>
									
									<div class="form-group  row col-sm-6">
										<label><?php echo $lang['PASSWORD'];?>:</label>
										<div class="input-group">
											<div class="input-group-prepend">
												<span class="input-group-text"><i class="fas fa-key text-primary"></i></span>
											</div>
											<input type="text" class="form-control" name="password2" /> 
										</div>
									</div>
									
									<div id="dbinfo">
									
										<div class="form-group  row col-sm-6">
											<label><?php echo $lang['HOST_NAME'];?>:</label>
											<div class="input-group">
												<div class="input-group-prepend">
													<span class="input-group-text"><i class="fas fa-home text-primary"></i></span>
												</div>
												<input type="text" class="form-control" name="host2"  value="" />
											</div>
										</div>
										<div class="form-group  row col-sm-6">
											<label><?php echo $lang['DATABASE_NAME'];?>:</label>
											<div class="input-group">
												<div class="input-group-prepend">
													<span class="input-group-text"><i class="fas fa-database text-primary"></i></span>
												</div>
												<input type="text" class="form-control" name="database2"  value="" />
											</div>
										</div>
									
									</div>
									
									<div class="form-group">
										<label for="inputEmail3" class="col-form-label"><i class="fas fa-file-alt text-primary"></i> <?php echo $lang['OTHER'];?>:</label>
										<div class="col-sm-6">
											<textarea class="form-control" name="comment2"></textarea>
										</div>
									</div>
									
									<!-- /.card-body -->
									<div class="card-footer col-sm-6">
										<button type="submit" value="Submit" name="create" id="create" class="btn btn-info float-right"><?php echo $lang['ADD_DATA'];?></button> <strong><?php echo $lang['PLEASE_NOTE'];?>:</strong> <?php echo $lang['FIELDS_ENCRYPTED_IN_DB'];?>
										
										<input value="yes" name="edit_page22" type="hidden" />
										<input value="<?php echo $id;?>" name="id" type="hidden" />
										<input value="<?php echo $id;?>" name="domainID" type="hidden" />
										<input value="<?php echo $domain;?>" name="domainName" type="hidden" />
										<input value="<?php echo $clientID;?>" name="clientID" type="hidden" />
									</div>
									<!-- /.card-footer -->
									
								</form>
								
							</div>
							
						</div>
					</div>
						
				</section>
				<?php } ?>
				
				<?php } ?>
							
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
	
		<!-- InputMask -->
		<script src="assets/js/moment/moment.min.js"></script>
		<!-- date-range-picker -->
		<script src="assets/js/daterangepicker/daterangepicker.js"></script>
		<script type="text/javascript">
			$(function() {
				$('input[name="registrationDate"]').daterangepicker({
					locale: { format: 'YYYY-MM-DD' },
					singleDatePicker: true,
					showDropdowns: true,
					minYear: (1990),
					maxYear: (2030),
					drops: 'up', //('down'/'up'/'auto')
					autoApply: true
				});
				$('input[name="renewalDate"]').daterangepicker({
					locale: { format: 'YYYY-MM-DD' },
					singleDatePicker: true,
					showDropdowns: true,
					minYear: (1990),
					maxYear: (2030),
					drops: 'up', //('down'/'up'/'auto')
					autoApply: true
				});
				
				$("#dbinfo").hide();
			});
			function checkType(value){
				if(value=="2"){ 
					$("#dbinfo").slideDown(); 
				} else { 
					$("#dbinfo").slideUp(); 
				}
			}
		</script>
		
	</body>

</html>
<?php } ?>