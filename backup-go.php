<?php

	session_start();
    include("includes/dbconnect.php"); //Load the settings
	require_once("includes/functions.php"); //Load the functions
	$msg="";
	
	if (!isset($_SESSION["username"]) && isset($_COOKIE["username"])) {
		$_SESSION["username"] = $_COOKIE["username"];
	}
	if (!isset($_SESSION["username"])) {
		header("Location: index.php");
	exit();
	
	} else {
	
	$action = (!empty($_REQUEST["action"]))?strip_tags(str_replace("'","`",$_REQUEST["action"])):'';

		if(!empty($action) && $action=="backup"){
			if($demo){
				echo "Sorry, this functionality disabled in demo.";
			} else {
				require_once("includes/db.backup.class.php");
				$date=date("Y-m-d-H-i-s");
				$bckp = new db_backup("backups/adm_backup_".$date.".txt");
				$bckp -> Backup($db_host,"3306",$db_user,$db_password,$db_name,$mysqli);
				//$bckp -> download("backups/adm_backup_".$date.".txt");
			}
		}
	}
?>