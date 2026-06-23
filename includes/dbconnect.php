<?php

	@include("config/mysql.php");
	
	$demo=false;
	global $mysqli;
	//if(!empty($db_host) && !empty($db_user) && !empty($db_password) && !empty($db_name)){ // Live Site
	if(!empty($db_host) && !empty($db_user) && !empty($db_name)){ // Localhost testing
		$mysqli = mysqli_connect($db_host, $db_user, $db_password, $db_name)or die("1. Open dbconnect.php and edit mysql variables. <br/> 2. Run install.php ");
		if (!$mysqli) {
			echo ("1. Open dbconnect.php and edit mysql variables. <br/> 2. Run install.php ");
			mysqli_query($mysqli,'SET NAMES utf8');
		}
	} else { echo "<p><strong>Application not installed!</strong> <a href='install.php'>Click here</a> to proceed with installation.</p>"; exit(); }