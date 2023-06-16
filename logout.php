<?php

	session_start();
    require_once("includes/dbconnect.php"); //Load the settings
	$msg="";
	
	if($_SESSION["logged_in"]!=true){ 
		header("Location: index.php");
	} else {
		$_SESSION['idUser']="";
		$_SESSION['username']= "";
		$_SESSION['accesslevel']= "";
		$_SESSION['logged_in'] = false;
		session_destroy();
		header("Location: index.php");
	}
?>