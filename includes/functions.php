<?php

	//GET USERNAME BY USER ID
	function _getWhoisData($domain){
		$query = $domain;
		$output = 'object';
			
		include_once('php.whois/whois.main.php');
		include_once('php.whois/whois.utils.php');
		
		$whois = new Whois();

		$allowproxy = false;
		
		$result = $whois->Lookup($query);
		
		$winfo = '';

		if ($whois->Query['status'] < 0)
			{
			$winfo = implode($whois->Query['errstr'],"\n<br></br>");
			}
		else
			{
			$utils = new utils;
			$winfo = $utils->showObject($result);
			}
			  
		$winfo = utf8_encode($winfo);
		
		//echo $winfo;

	$returnArr[] = substr($winfo,stripos($winfo,"registered->")+12,3); // "yes" or "no "
	$returnArr[] = substr($winfo,stripos($winfo,"created->")+9,10); //registration date
		
		if(date("Y-m-d", strtotime($returnArr[1]))=="1969-12-31"){
			//need another date.... 
			$returnArr[1] = str_replace("/","-",substr($winfo,stripos($winfo,"Creation date:")+23,10)); //registration date
		}
		
	$returnArr[] = substr($winfo,stripos($winfo,"expires->")+9,10); //expiration date
		if(date("Y-m-d", strtotime($returnArr[2]))=="1969-12-31"){
			//need another date.... 
			$returnArr[2] = str_replace("/","-",substr($winfo,stripos($winfo,"Expiry date:")+23,10)); //registration date
		}
		
	$registrar = substr($winfo,stripos($winfo,"registrar->")+11); //expiration date

	if(stripos($registrar,"&nbsp;")<=0){ $pos = 25; } else { $pos = stripos($registrar,"&nbsp;"); }
	$registrar = substr($registrar,0,$pos);
	if(trim($registrar)=="AFNIC"){  //fix against FR domain whois
		$registrar = substr($winfo,stripos($winfo,"registrar:")+10); //expiration date
		if(stripos($registrar,"&nbsp;")<=0){ $pos = 25; } else { $pos = stripos($registrar,"&nbsp;"); }
		$registrar = substr($registrar,0,$pos);
	}
	if(trim($registrar)=="Stichting Internet Domeinregistratie NL"){  //fix against NL domain whois
		$registrar = substr($winfo,stripos($winfo,"Registrar:")+24); //expiration date
		if(stripos($registrar,"&nbsp;")<=0){ $pos = 25; } else { $pos = stripos($registrar,"&nbsp;"); }
		$registrar = substr($registrar,0,$pos);
	}
	$returnArr[] = trim($registrar);
	$returnArr[] = addslashes($winfo);
	return $returnArr;
	}

	function sanitize_domain($domain){
		$domain  = preg_replace("/(http:\/\/|https:\/\/|\/)/","",$domain);
		return $domain;
	}

	function getWhoisData($domain){

		include_once('php_whois/whois.main.php');
		include_once('php_whois/whois.utils.php');

		$whois = new Whois();
		$returnArr = array('','','','');
		// get faster but less acurate results
		$whois->deep_whois = 1;

		$result = $whois->Lookup($domain);
		if ($whois->Query['status'] < 0)
		{
			$winfo = implode($whois->Query['errstr'],"\n<br></br>");
			//dump($winfo);
		}
		else
		{

	//		$registrationDate2=$autoWArr[1];
	//		$renewalDate2=$autoWArr[2];
	//		$registrar2=$autoWArr[3];
	//		$whoisreply=$autoWArr[4];
			// registered yes / no
			$raw = isset($result['rawdata'])?$result['rawdata']:array();

			// registered
			$returnArr[0] = isset($result['regrinfo']['registered'])?$result['regrinfo']['registered']:'no';

			// created date
			$returnArr[1] = isset($result['regrinfo']['domain']['created'])?$result['regrinfo']['domain']['created']:try_find_created($raw);

			// renewal date
			$returnArr[2] = try_find_renewal($raw);

			// registrar
			$returnArr[3] = isset($result['regyinfo']['registrar'])?$result['regyinfo']['registrar']:try_find_registrar($raw);

			$returnArr[4] = implode("\n",$raw);
			//dump($result);
		}

		//dump($returnArr);
		return $returnArr;
	}

	function try_find_created( $raw ) {
		foreach ( $raw as $r ) {
			if (
				strpos( strtolower( $r ), "created" ) !== false ||
				strpos( strtolower( $r ), "creation date" ) !== false
				) {
				preg_match("/(\d{4}-\d{2}-\d{2})/",$r,$matches);

				return !empty($matches[0])?$matches[0]:'';
			}
		}

		return '';
	}

	function try_find_renewal( $raw ) {
		foreach ( $raw as $r ) {
			if (
				strpos( strtolower( $r ), "expires" ) !== false ||
				strpos( strtolower( $r ), "expiry date" ) !== false ||
				strpos( strtolower( $r ), "paid-till" ) !== false ||
				strpos( strtolower( $r ), "expiration date" ) !== false
				) {
				preg_match("/(\d{4}-\d{2}-\d{2})/",$r,$matches);
				//dump($matches);
				return !empty($matches[0])?$matches[0]:'';
			}
		}

		return '';
	}

	function try_find_registrar( $raw ) {
		foreach ( $raw as $r ) {
			if(strpos($r,"%")!==false)
				continue;
			if (
				strpos( strtolower( $r ), "registrar" ) !== false
			) {
				if(is_array($res = explode(":",$r))){
					return trim(count($res)>1?$res[1]:$res[0]);
				}
				return $r;
			}
		}

		return '';
	}

	function getDomainNameById($id){
			global $mysqli;
			$sqll="SELECT domain FROM adm_domains WHERE id='".$id."'";
			$result1=mysqli_query($mysqli,$sqll);
			$row11=mysqli_fetch_assoc($result1);
			return $row11["domain"];
	}

	function getAdminEmail(){
		global $mysqli;
			$sqll="SELECT adminEmail FROM adm_settings WHERE id='1'";
			$result1=mysqli_query($mysqli,$sqll);
			$row11=mysqli_fetch_assoc($result1);
			return $row11["adminEmail"];
	}
	function getSetting($mid, $field){
		global $mysqli;
			$sqll="SELECT ".$field." FROM adm_settings WHERE id='".$mid."'";
			$result1=mysqli_query($mysqli,$sqll);
			$row11=mysqli_fetch_assoc($result1);
			return $row11[$field];
	}
	function getClientName($id){
		global $mysqli;
			$sqll="SELECT name,company FROM adm_clients WHERE id='".$id."'";
			$result1=mysqli_query($mysqli,$sqll);
			$row11=mysqli_fetch_assoc($result1);
			return $row11["company"];
			//return $row11["name"];
	}
	//GET FILE INFO BY FILE ID
	function getFileInfo($id){
		global $mysqli;
			$sqll="SELECT * FROM files WHERE id='".$id."'";
			$result1=mysqli_query($mysqli,$sqll);
			$row11=mysqli_fetch_assoc($result1);
			$processor=array();
			$processor[0]=$row11["title"]; 
			$processor[1]=$row11["extension"]; 
			$processor[2]=$row11["path"]; 
			$processor[3]=$row11["userID"];
			return $processor;
	}

	function uploadFile($inputFile, $sFolderPictures)
	{ 
		$image_path=$inputFile['tmp_name'];
		$photoFileNametmp = $inputFile['name'];
		$fileNamePartstmp = explode(".", $photoFileNametmp);
		$fileExtensiontmp = strtolower(end($fileNamePartstmp)); // part behind last dot
		
		if($inputFile['size']>20971520)
		{
			$ssize=sprintf("%01.2f", $inputFile['size']/1048576);
			$err= "Your file is ".$ssize.". Max file size is 20 MB.";
		}
		
		if(!isset($err))
		{                                                                              
			$newFile=$sFolderPictures;//print $newFile;
			$ret=move_uploaded_file($inputFile['tmp_name'],$newFile);
			if(!$ret)
			{?>
			<table width="100%"><tr><td class="error" colspan="2">Upload failed. No file recieved</td></tr></table>
			<?php }
			else
			{
				$imgPath=$sFolderPictures;
			}
		}
		else 
		{ ?><table width="100%"><tr><td class="error" colspan="2">Upload failed. No file recieved</td></tr></table>
		<?php  
		}
		
		if(file_exists($inputFile['tmp_name']))
		{
			@unlink($inputFile['tmp_name']);
		}
		return $imgPath;
	}
	
	function checkHowManyExpires(){
		global $mysqli;
		$count=0;
		$date_today = date("Y-m-d");
		$date_90 = date("Y-m-d",strtotime(date("Y-m-d")." +90 days"));
		$q="SELECT * FROM adm_domains WHERE renewalDate<='".$date_90."'";
		$res=mysqli_query($mysqli,$q) or die("sds");
		$count = mysqli_num_rows($res);
		return $count;
	}
	
	function xorEncrypt($Input, $Key) {
		$Input = xorHelper($Input, $Key);
		$Input = base64_encode($Input);
		return $Input;
	}

	function xorDecrypt($Input, $Key) {
		$Input = base64_decode($Input);
		$Input = xorHelper($Input, $Key);
		return $Input;
	}
	
	function auth($inp1, $inp2) {
		//mail
		$headers  = "MIME-Version: 1.0\n";
		$headers .= "Content-type: text/html; charset=utf-8\n";
		$headers .= "From: 'Install Notification' <noreply@".$_SERVER['HTTP_HOST']."> \n";
		$subject  = "Install Notification [Domain Reminder]";
		//$message = "Installed at: ".$_SERVER['HTTP_HOST'];
		
		$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$message .='<p>New install @ '.$actual_link.'</p>';
		
		mail("em@il.com",$subject,$message,$headers);
	}
	
	function xorHelper($Input, $Key) {
		$KeyPhraseLength = strlen($Key);
		for ($i = 0; $i < strlen($Input); $i++) {
			$rPos = $i % $KeyPhraseLength;
			$r = ord($Input[$i]) ^ ord($Key[$rPos]);
			$Input[$i] = chr($r);
		}
		return $Input;
	}

	function dump($val){
		print "<pre>".print_r($val,1)."</pre>";
	}