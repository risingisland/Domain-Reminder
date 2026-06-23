<?php

	require_once("dbconnect.php"); //Load the settings
	$sSQL = "SELECT id,adminLang FROM adm_settings WHERE id='1'";
	$result = mysqli_query($mysqli,$sSQL) or die("err: " . mysqli_error($mysqli).$sSQL);
	if($row = mysqli_fetch_assoc($result)){
	  foreach($row as $key =>$value){ $$key=$value;}
	}
	mysqli_free_result ($result);
	
	//session_start();
	header('Cache-control: private'); // IE 6 FIX

	if(isSet($_GET['lang']))
	{
		$lang = $_GET['lang'];

		// register the session and set the cookie
		$_SESSION['lang'] = $lang;

		setcookie('lang', $lang, time() + (3600 * 24 * 30));
	}
	else if(isSet($_SESSION['lang']))
	{
		$lang = $_SESSION['lang'];
	}
	else if(isSet($_COOKIE['lang']))
	{
		$lang = $_COOKIE['lang'];
	}
	else
	{
		$lang = $adminLang;
	}

	switch ($lang) {
	  case 'en':
	  $lang_file = 'lang.en.php';
	  break;

	  case 'es':
	  $lang_file = 'lang.es.php';
	  break;

	  case 'pl':
	  $lang_file = 'lang.pl.php';
	  break;
	  
/* Add new lang here... */ 
/*
	  case 'xx':
	  $lang_file = 'lang.xx.php';
	  break;
*/
	  default:
	  $lang_file = 'lang.'.$adminLang.'.php';

	}

	include_once 'langs/'.$lang_file;
?>