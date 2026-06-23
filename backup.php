<?php

	use function Sodium\add;

	session_start();
	require_once( "includes/dbconnect.php" ); //Load the settings
	require_once( "includes/functions.php" ); //Load the functions
	require_once("includes/languages.php"); //Load the langs
	$msg  = "";
	$msg2 = "";
	
	if (!isset($_SESSION["username"]) && isset($_COOKIE["username"])) {
		$_SESSION["username"] = $_COOKIE["username"];
	}
	if (!isset($_SESSION["username"])) {
		header("Location: index.php");
	exit();
	
	} else {

		$action = ( ! empty( $_REQUEST["action"] ) ) ? strip_tags( str_replace( "'", "`", $_REQUEST["action"] ) ) : '';
		$domain = ( ! empty( $_REQUEST["domain"] ) ) ? strip_tags( str_replace( "'", "`", $_REQUEST["domain"] ) ) : '';

		if ( ! empty( $action ) && $action == "restore" ) {
			if ( $demo ) {
				$msg = '<div class="alert alert-danger alert-dismissible">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
							<h5><i class="icon fas fa-exclamation-triangle"></i> Sorry, this functionality disabled in demo.</h5>
						</div>';
			} else {
				if ( ! empty( $_FILES['sqlinput']['name'] ) ) {
					$photoFileNametmp = $_FILES['sqlinput']['name'];
					$fileNamePartstmp = explode( ".", $photoFileNametmp );
					$counter2         = count( $fileNamePartstmp ) - 1;
					$fileExtensiontmp = strtolower( $fileNamePartstmp[ $counter2 ] ); // part behind last dot
					if ( $fileExtensiontmp != "txt" ) {
						$msg = '<div class="alert alert-warning alert-dismissible">
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
									<h5><i class="icon fas fa-exclamation-triangle"></i> Input file must have .txt extension</h5>
								</div>';
					} else {
						$date     = date( "Y-m-d-H-i-s" );
						$imgPath  = uploadFile( $_FILES['sqlinput'], "backups/upl_adm_backup_" . $date . "." . $fileExtensiontmp );
						$sqlfile  = "backups/upl_adm_backup_" . $date . "." . $fileExtensiontmp;
						$contents = file_get_contents( $sqlfile );
						// Remove comments
						$comment_patterns = array(
							'/\/\*.*(\n)*.*(\*\/)?/', //C comments
							'/^\s*--.*\n/', //comments start with --
							'/^\s*#.*\n/m', //comments start with #
						);
						$contents         = preg_replace( $comment_patterns, "\n", $contents );
						
						$err = 0;
						if ( mysqli_multi_query( $mysqli, $contents ) ) {
							do {
								/* getting first result set */
								if ( $result = mysqli_store_result( $mysqli ) ) {
									while ( $row = mysqli_fetch_row( $result ) ) {
									//printf("%s\n", $row[0]);
									}
									mysqli_free_result( $result );
								}
								/* print devider */
								if ( mysqli_more_results( $mysqli ) ) {
								//printf("-----------------\n");
								}
							} while ( @mysqli_next_result( $mysqli ) );

							$msg = '<div class="alert alert-success alert-dismissible">
										<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
										<h5><i class="icon fas fa-check"></i> File uploaded and executed.</h5>
									</div>';
						} else {
							$msg .= '<div class="alert alert-warning alert-dismissible">
										<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
										<h5><i class="icon fas fa-exclamation-triangle"></i> Unable to run query ' . $contents . ': ' . mysqli_error( $mysqli ) .' </h5>
									</div> ';
						}

					}
				}
			}
		}

		if ( ! empty( $action ) && $action == "restore-domain" ) {
			if ( $demo ) {
				$msg2 = '<div class="alert alert-danger alert-dismissible">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
							<h5><i class="icon fas fa-exclamation-triangle"></i> Sorry, this functionality disabled in demo.</h5>
						</div>';
			} else {
				if ( ! empty( $_FILES['sqlinput-domain']['name'] ) ) {
					$photoFileNametmp = $_FILES['sqlinput-domain']['name'];
					$fileNamePartstmp = explode( ".", $photoFileNametmp );
					$counter2         = count( $fileNamePartstmp ) - 1;
					$fileExtensiontmp = strtolower( $fileNamePartstmp[ $counter2 ] ); // part behind last dot
					if ( $fileExtensiontmp != "txt" ) {
						$msg = '<div class="alert alert-warning alert-dismissible">
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
									<h5><i class="icon fas fa-exclamation-triangle"></i> Input file must have .txt extension</h5>
								</div>';
					} else {
						$date = date( "Y-m-d-H-i-s" );
						@mkdir( "backups/" . $domain, 0777, true );
						$imgPath  = uploadFile( $_FILES['sqlinput-domain'], "backups/" . $domain . "/upl_bckp_" . $date . "." . $fileExtensiontmp );
						$sqlfile  = "backups/" . $domain . "/upl_bckp_" . $date . "." . $fileExtensiontmp;
						$contents = file_get_contents( $sqlfile );
						// Remove comments
						$comment_patterns = array(
							'/\/\*.*(\n)*.*(\*\/)?/', //C comments
							'/\s*--.*\n/', //comments start with --
							'/\s*#.*\n/', //comments start with #
						);
						$contents         = preg_replace( $comment_patterns, "\n", $contents );
						//Retrieve sql statements
						$statements = explode( ";", $contents );
						//$statements = preg_replace("/\s/", ' ', $statements);
						$host     = "";
						$user     = "";
						$password = "";
						$name     = "";
						$q        = "SELECT id,clientID FROM adm_domains WHERE domain='" . $domain . "'";
						$res      = mysqli_query( $mysqli, $q );
						if ( mysqli_num_rows( $res ) > 0 ) {
							$rr       = mysqli_fetch_assoc( $res );
							$id       = $rr["id"];
							$clientID = $rr["clientID"];
							$jks      = xorEncrypt( $domain, strtoupper( $domain ) );
							$q        = "SELECT * FROM adm_data WHERE type='2' AND dd='" . $jks . "'";
							$res      = mysqli_query( $mysqli, $q );
							if ( mysqli_num_rows( $res ) > 0 ) {
								$rr       = mysqli_fetch_assoc( $res );
								$host     = xorDecrypt( $rr["ee"], $clientID . $domain . $id );
								$user     = xorDecrypt( $rr["aa"], $domain . $id . $clientID );
								$password = xorDecrypt( $rr["bb"], $clientID . $domain . $id );
								$name     = xorDecrypt( $rr["ff"], $clientID . $domain . $id );
							}
						}
						if ( ! empty( $host ) && ! empty( $user ) && ! empty( $password ) && ! empty( $name ) ) {
							$link = mysql_connect( $host, $user, $password ) or die( "error " );
							@mysql_select_db( $name );
							mysqli_query( $mysqli, "SET NAMES utf8" ) or die( "err: " . mysqli_error( $mysqli ) );
							foreach ( $statements as $query ) {
								if ( trim( $query ) != '' ) {
									$result = mysqli_query( $mysqli, $query );
									if ( ! $result ) {
										$msg2 .= '<div class="alert alert-warning alert-dismissible">
													<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
													<h5><i class="icon fas fa-exclamation-triangle"></i> Unable to run query ' . $query . ': ' . mysqli_error( $mysqli ) .'</h5>
												</div>';
									}
								}
							}
							$msg2 = '<div class="alert alert-success alert-dismissible">
										<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
										<h5><i class="icon fas fa-check"></i> File uploaded and executed.</h5>
									</div>';

							$link = mysql_connect( $db_host, $db_user, $db_password ) or die( "1. Open dbconnect.php and edit mysql variables. <br/> 2. Run install.php " );
							@mysql_select_db( $db_name );
							mysqli_query( $mysqli, "SET NAMES utf8" ) or die( "err: " . mysqli_error( $mysqli ) );
						} else {
							$msg2 .= '<div class="alert alert-warning alert-dismissible">
										<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
										<h5><i class="icon fas fa-exclamation-triangle"></i> Error during connection to database process.</h5>
									</div>';
						}
					}
				}
			}
		}

		if ( ! empty( $action ) && $action == "update" ) {
			//select all domains, put in array, then one by one update whois info.
			$q      = "SELECT * FROM adm_domains ORDER BY domain ASC";
			$res    = mysqli_query( $mysqli, $q );
			$domArr = array();
			if ( mysqli_num_rows( $res ) > 0 ) {
				while ( $rr = mysqli_fetch_assoc( $res ) ) {
					$domArr[ $rr["id"] ] = $rr["domain"];
				}

				foreach ( $domArr as $key => $value ) {
					$autoWArr = getWhoisData( $value );//new function - auto-whois checker, created by me, using http://www.phpwhois.com/ class
					if ( $autoWArr[0] == "yes" ) {
						//domain registered
						$registrationDate = $autoWArr[1];
						$renewalDate      = $autoWArr[2];
						$registrar        = $autoWArr[3];
						$whoisreply       = $autoWArr[4];
						$fail             = false;
						if ( ! is_numeric( str_replace( "-", "", $registrationDate ) ) ) {
							$registrationDate = '0000-00-00';
							$msg             .= '<div class="alert alert-warning alert-dismissible">
														<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
														<h5><i class="icon fas fa-exclamation-triangle"></i> Domain Whois failed for registration date.</h5>
													</div>';
							$fail             = true;
						}
						if ( ! is_numeric( str_replace( "-", "", $renewalDate ) ) ) {
							$renewalDate = '0000-00-00';
							$msg        .= '<div class="alert alert-warning alert-dismissible">
												<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
												<h5><i class="icon fas fa-exclamation-triangle"></i> Domain whois failed for renewal date.</h5>
											</div>';
							$fail        = true;
						}

						$whoisreply = addslashes( $whoisreply );
						$registrar  = addslashes( $registrar );
						if ( $fail ) {
							$sql = "UPDATE adm_domains SET registrar='" . $registrar . "',whoisreply='" . $whoisreply . "' WHERE id='" . $key . "'";
						} else {
							$sql = "UPDATE adm_domains SET renewalDate='" . $renewalDate . "',registrationDate='" . $registrationDate . "',registrar='" . addslashes($registrar) . "',whoisreply='" . addslashes($whoisreply) . "' WHERE id='" . $key . "'";
						}

						$result = mysqli_query( $mysqli, $sql ) or die( "oopsy, error occured when tryin to update domain." . mysqli_error( $mysqli ) );
					} else {
						$sql = "UPDATE adm_domains SET whoisreply='" . addslashes( $autoWArr[4] ) . "' WHERE id='" . $key . "'";
						$result = mysqli_query( $mysqli, $sql ) or die( "oopsy, error occured when tryin to update domain." . mysqli_error( $mysqli ) );
					}
				}
				$msg = '<div class="alert alert-success alert-dismissible">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
							<h5><i class="icon fas fa-check"></i> Database succesfully updated.</h5>
						</div>';
			}

		}

		//get all domains with database info for them:
		$domains_1 = "";

		$q = "SELECT `domain` FROM adm_domains ORDER BY `domain` ASC";
		$res = mysqli_query( $mysqli, $q ) or die( mysqli_error( $mysqli ) );
		if ( mysqli_num_rows( $res ) > 0 ) {
			while ( $rr = mysqli_fetch_assoc( $res ) ) {
				$jks  = xorEncrypt( $rr["domain"], strtoupper( $rr["domain"] ) );
				$q2   = "SELECT dd FROM adm_data WHERE type='2' AND dd='" . $jks . "'";
				$res2 = mysqli_query( $mysqli, $q2 );
				if ( mysqli_num_rows( $res2 ) > 0 ) {
					$domains_1 .= "<option value='" . $rr["domain"] . "'>" . $rr["domain"] . "</option>";
				}
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
								<a href="backup.php" class="nav-link active"> <i class="nav-icon fas fa-database"></i>
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
								<h1 class="m-0 text-primary"><i class="fas fa-database"></i> <?php echo $lang['DATABASES']; ?></h1> 
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
						
							<div class="col-md-6">
								<?php echo $msg2; ?>
							</div>
							
							<div class="col-lg-12">
								<div class="card card-primary card-outline">
								
									<div class="card-header">
										<h5 class="m-0 text-info text-primary"><?php echo $lang['SYSTEM_DATABASE']; ?></h5> 
									</div>
									
									<div class="card-body">
										<table class="table">
											<thead>
												<tr>
													<th width="20%"> </th>
													<th width="25%"> </th>
													<th> </th>
												</tr>
											</thead>
											<tbody>
											
												<tr>
													<td><i class="fas fa-download text-success fa-2x"></i> <strong><?php echo $lang['DB_BACKUP']; ?>:</strong></td>
													<td>
														<form action="backup-go.php" target="_blank" enctype="multipart/form-data" method="post" name="ff1">
															<input type="hidden" value="backup" name="action"/>
															<button type="button" name="create2" id="create2" onclick="launchDownloader('1','');" class="btn btn-block btn-success"><?php echo $lang['BACKUP']; ?></button>
														</form>
													</td>
													<td>
														<p><i class="fas fa-info-circle text-primary"></i> <?php echo $lang['BACKUP_TEXT']; ?></p>
													</td>
												</tr>
											
												<tr>
													<td><i class="fas fa-upload text-warning fa-2x"></i> <strong><?php echo $lang['DB_RESTORE']; ?>:</strong></td>
													<td>
														<form action="backup.php" enctype="multipart/form-data" method="post" name="ff1">
															<div class="input-group">
																<div class="custom-file">
																	<input type="hidden" value="restore" name="action" class="custom-file-input"/>
																	<input type="file" name="sqlinput" class="custom-file-input"/>
																	<label class="custom-file-label" for="exampleInputFile">...</label>
																</div>
																<div class="input-group-append">
																	<button type="submit" name="create2" id="create2" class="btn btn-block btn-warning"><?php echo $lang['RESTORE']; ?></button>
																</div>
															</div>
														</form>
														<!--form action="backup.php" enctype="multipart/form-data" method="post" name="ff1">
															<input type="hidden" value="restore" name="action"/>
															<input type="file" name="sqlinput"/>
															<button type="submit" name="create2" id="create2" class="btn btn-block btn-warning">Restore</button>
														</form-->
													</td>
													<td>
														<p><i class="fas fa-info-circle text-primary"></i> <?php echo $lang['RESTORE_TEXT']; ?></p>
													</td>
												</tr>
											
												<tr>
													<td><i class="fas fa-retweet text-danger fa-2x"></i> <strong><?php echo $lang['DB_UPDATE']; ?>:</strong></td>
													<td>
														<form action="backup.php" enctype="multipart/form-data" method="post" name="ff1">
															<input type="hidden" value="update" name="action"/>
															<button type="submit" name="create2" id="create2" class="btn btn-block btn-danger"><?php echo $lang['UPDATE']; ?></button>
														</form>
														
													</td>
													<td>
														<p><i class="fas fa-info-circle text-primary"></i> <?php echo $lang['UPDATE_TEXT']; ?></p>
													</td>
												</tr>
												
											</tbody>
										</table>
									</div>
									
								</div>
							</div>
						
							<!-- /col-lg-12 -->
							
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
		
		<script language="javascript" type="text/javascript">
            function launchDownloader(type, field) {
                if (field != "") {
                    field = document.getElementById(field).value;
                }
                if (type == "1") {
                    window.open('backup-go.php?action=backup', null, 'height=200,width=400,status=yes,toolbar=no,menubar=no,location=no');
                } 
            }
        </script>
		
	</body>

</html>
<?php } ?>