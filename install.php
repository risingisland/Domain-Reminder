<?php

	//Load the database file
	$tt = "";
	$continue = true;
	$success = false;
	
	//1. check that config/ is writable
	//2. if not - throw error, else show form.
	//3. form will have 4 fields for database and 1 field for license key and 1 key for user to enter future domain name for this license key.
	//4. after form submitted we need to show success message and further instructions.
	
	if(!is_writable("config/")){ 
		@chmod("config/", 0777);
		if(!is_writable("config/")){ 
			//chmoding didn't help. throw error
			$continue = false;
			$tt = '<div class="form-group"><i class="fas fa-exclamation-triangle text-warning"></i><b>ERROR!</b> Please set chmod 755 or 777 for directory "config"</div>';
		}
	}
	
	if(!is_writable("backups/")){ 
		@chmod("backups/", 0777);
		if(!is_writable("backups/")){ 
			//chmoding didn't help. throw error
			$continue = false;
			$tt = '<div class="form-group"><i class="fas fa-exclamation-triangle text-warning"></i><b>ERROR!</b> Please set chmod 755 or 777 for directory "backups"</div>';
		}
	}

	$dbn = 			(!empty($_REQUEST['dbn']))?strip_tags(str_replace("'","`",$_REQUEST['dbn'])):'';
	$dbp = 			(!empty($_REQUEST['dbp']))?strip_tags(str_replace("'","`",$_REQUEST['dbp'])):'';
	$dbu = 			(!empty($_REQUEST['dbu']))?strip_tags(str_replace("'","`",$_REQUEST['dbu'])):'';
	$dbh = 			(!empty($_REQUEST['dbh']))?strip_tags(str_replace("'","`",$_REQUEST['dbh'])):'';
	
	if($continue){
	//LOGIN VARIABLES

		// LOGIN
		if(!empty($_REQUEST["install"]) && $_REQUEST['install']=="yes"){
			//if($dbn=="" || $dbp=="" || $dbu=="" || $dbh==""){ // Live Site
			if($dbn=="" || $dbu=="" || $dbh==""){ // Localhost Testing
				$tt = '<div class="form-group"><i class="fas fa-exclamation-triangle text-warning"></i> Some fields were left empty. All fields are mandatory. Try again.</div>';
			} else {

				//check DB connection.
				if ( $mysqli = @mysqli_connect( $dbh, $dbu, $dbp, $dbn ) ) {
				} else {
					$continue = false;
					$tt  = '<div class="form-group"><i class="far fa-times-circle text-danger"></i> <b>ERROR!</b> Couldn\'t connect to database with provided information. <br />Please check your input and try again.</div>';
				}
				
				if($continue){
					//create mysql.php file
					$ourFileName = "config/mysql.php";
					$fh = fopen($ourFileName, 'w+');
					
$stringData = "<?php 
	\$db_host = '".$dbh."'; //hostname
	\$db_name = '".$dbn."'; //database name 
	\$db_user = '".$dbu."'; // username
	\$db_password = '".$dbp."'; // password
?>";
					
					fwrite($fh, $stringData);
					fclose($fh);
					
					require_once("includes/dbconnect.php");
					require_once("includes/functions.php");
					
								
					//create Clients
					$query = "CREATE TABLE adm_clients
						(
					   id int NOT NULL AUTO_INCREMENT,
						name varchar(255),
						comment longtext,
						company varchar(255),
						job_title varchar(255),
						email varchar(255),
						web varchar(255),
						phone varchar(255),
						address varchar(255),
						dateCreated datetime DEFAULT NULL,
						PRIMARY KEY (id)
						)";
					if(mysqli_query($mysqli,$query)){
						$tt .= '<div class="form-group"><i class="fas fa-check text-success"></i> Created table "adm_clients" (1/4)</div>';
					} else { $tt .= '<div class="form-group"><i class="far fa-times-circle text-danger"></i> <b>ERROR!</b> can\'t create adm_clients (1/4)!<br /></div>'; $continue = false; }
					
					//create domains
					$query = "CREATE TABLE adm_domains
						(
						id int NOT NULL AUTO_INCREMENT,
						registrar text,
						domain varchar(255),
						comment longtext,
						clientID int(20),
						whoisreply LONGTEXT,
						renew_link TEXT,
						renewalDate date DEFAULT NULL,
						dateCreated datetime DEFAULT NULL,
						registrationDate date DEFAULT NULL,
						PRIMARY KEY (id)
						)";
					if(mysqli_query($mysqli,$query)){
						$tt .= '<div class="form-group"><i class="fas fa-check text-success"></i> Created table "adm_domains" (2/4)</div>';
					} else { $tt .= '<div class="form-group"><i class="far fa-times-circle text-danger"></i> <b>ERROR!</b> can\'t create adm_domains (2/4)!</div>'; $continue = false; }	
					//create SETTINGS
					$query = "CREATE TABLE adm_settings
						(
						id int NOT NULL AUTO_INCREMENT,
						username varchar(255),
						password varchar(255),
						adminEmail varchar(255),
						adminLang varchar(2),
						show_debug INT(5) DEFAULT '1',
						show_domdata INT(5) DEFAULT '1',
						PRIMARY KEY (id)
						)";
					if(mysqli_query($mysqli,$query)){
						$tt .=  '<div class="form-group"><i class="fas fa-check text-success"></i> Created table "settings" (3/4)</div>';
						$query="INSERT INTO adm_settings (username,password,adminEmail,adminLang) VALUES ('admin','1a1dc91c907325c69271ddf0c944bc72','demo@email.com','en')";
						if(mysqli_query($mysqli,$query)){
							$tt .= '<div class="form-group"><i class="fas fa-check text-success"></i> Default settings record created! </div>';
						}else { $tt .= '<div class="form-group"><i class="far fa-times-circle text-danger"></i> <b>ERROR!</b> can\'t create default admin account!</div>'; $continue = false;}
					} else { $tt .= '<div class="form-group"><i class="far fa-times-circle text-danger"></i> <b>ERROR!</b> can\'t create "adm_settings" (3/4)!</div>'; $continue = false; }
					//create domains
					$query = "CREATE TABLE adm_data
							(
							id int NOT NULL AUTO_INCREMENT,
							type tinyint(5) DEFAULT '4',
							aa longtext,
							bb longtext,
							cc longtext,
							dd longtext,
							ee longtext,
							ff longtext,
							PRIMARY KEY (id)
							)";
					if(mysqli_query($mysqli,$query)){
						$tt .= '<div class="form-group"><i class="fas fa-check text-success"></i> Created table "adm_data" (4/4)</div>';
					} else { $tt .= '<div class="form-group"><i class="far fa-times-circle text-danger"></i> <b>ERROR!</b> can\'t create "adm_data" (4/4)!<br /></div>'; $continue = false; }
					
					if($continue){
						$tt .= '<hr><div class="form-group"><p class="text-primary"><strong><i class="far fa-thumbs-up fa-2x"></i> Installation successful!</strong></p> <p>Please <span style="color:red;" >delete this file</span> now and go to your <a href="index.php">admin</a> page. </p></div>
						<div class="form-group"><p><i class="fas fa-user-lock text-info fa-2x"></i> Default username & password:<br> <b>admin / pass</b></p></div>
						<div class="form-group"><p>IMPORTANT: change CHMOD to 644 or 744 for directory "config"!</p></div>';
						$success = true;
						//$a = auth($dbn,$dbu); // send install successful message
						@chmod("config/mysql.php", 0644);
						//@chmod("config/", 0644);
					}
				}
			} 
		}
	}
	
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Install | Domain Reminder</title>
		<link rel="icon" type="image/png" sizes="96x96" href="assets/img/domain.png">

		<!-- Google Font: Source Sans Pro -->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
		<!-- Font Awesome -->
		<link rel="stylesheet" href="assets/js/fontawesome-free/css/all.min.css">
		<!-- icheck bootstrap -->
		<link rel="stylesheet" href="assets/js/icheck-bootstrap/icheck-bootstrap.min.css">
		<!-- Theme style -->
		<link rel="stylesheet" href="assets/css/adminlte.min.css">
	</head>
	
	<body class="hold-transition login-page">
		<div class="login-box">
			<div class="login-logo">
				<img src="assets/img/domain.png" alt="Logo" class="brand-image" style="width:48px;"> <b style="color:#1E81CE;">Domain Reminder</b>
			</div>
			<!-- /.login-logo -->
			<div class="card">
				<div class="card-body login-card-body">
					<?php if(!empty($tt)){ echo $tt; } 
					if($success){} else {?>
					
					<form method="post" action="install.php" enctype="multipart/form-data"  name="ff1">

						<div class="form-group">
							<p>Please enter your <strong>EXISTING</strong> database login information.</p>
							<p>All fields are <strong>mandatory</strong>.</p>
						</div>
						
						<div class="form-group">
							<label>Database Hostname:</label>
							<input class="form-control" type="text" id="dbh" name="dbh" value="<?php echo  $dbh?>" placeholder="localhost"/>
						</div>
						
						<div class="form-group">
							<label>Database Name:</label>
							<input class="form-control" type="text" id="dbn" name="dbn" value="<?php echo  $dbn?>" placeholder="db-domain-thing"/>
						</div>
						
						<div class="form-group">
							<label>Database Username:</label>
							<input class="form-control" type="text" id="dbu" name="dbu" value="<?php echo  $dbu?>" placeholder="root"/>
						</div>
						
						<div class="form-group">
							<label>Database Password:</label>
							<input class="form-control" type="password" id="dbp" name="dbp" value="<?php echo $dbp?>" placeholder="******"/>
						</div>
						
						<button type="submit" name="submit" value="Submit" class="btn btn-primary btn-block">Install</button>
						<input type="hidden" value="yes" name="install"  />
						
					</form>
					<?php } ?>
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

	</body>
</html>