<?php class db_backup {

    var $filename  ;
    function __construct($filename){
        $this -> filename = $filename ;
    }

    function Backup($db_host,$port="3306",$db_user,$db_password,$db_name,$mysqli)
    {
        $pwd=$db_password;
        $i		= 0;
        $crlf	= "\r\n";
        $schema_insert	= "";
        $host	= (empty($db_host))		? "localhost"	: $db_host;
        $port	= (empty($port))		? "3306"		: $port;
        $user	= (empty($db_user))		? "root"		: $db_user;
        $dbname	= (empty($db_name))	? die("No Database specified")		: $db_name;
        $password	= ($db_password == "")	? "NO" : "YES";
        $mysqli = @mysqli_connect($host.":".$port, $user, $pwd,$dbname) or die('Can\'t connect to database. Please, check database credentials');

        $sql = "SHOW TABLES FROM {$dbname}";
        $res = mysqli_query($mysqli,$sql);
        $tables = array();
        $num_tables		= mysqli_num_rows($res);
        while ($row = mysqli_fetch_assoc($res)) {
            $tables[]=$row['Tables_in_'.$dbname];
        }

        $Year		= date("Y");
        $Month		= date("m");
        $Day		= date("d");
        $search       = array("\x00", "\x0a", "\x0d", "\x1a");
        $replace      = array('\0', '\n', '\r', '\Z');
        $DataCreation	= strftime("%d %B %Y", mktime(0,0,0,$Month,$Day,$Year));
        $Time			= date("H:i:s");

        If (/*$this->dbHeaders == 0*/1)	{
            $content	= "";
            $content	.= $crlf;
            $content	.= "# --------------------------------------------------------".$crlf."";
            $content	.= "#".$crlf."";
            $content	.= "# Dump Script for '".$dbname."' ".$crlf."";
            $content	.= "#".$crlf."";
            $content	.= "# Host: ".$host."".$crlf."";
            $content	.= "#".$crlf."";
            $content	.= "# created on ".$DataCreation." at ".$Time."".$crlf."";
            $content	.= "#".$crlf."";
            $content	.= $crlf;
            $content	.= "# --------------------------------------------------------".$crlf."";

            $fp = fopen($this->filename, 'w+');
            foreach ( $tables as $table ) {
                $content	.= $crlf;
                $content	.= "# --------------------------------------------------------".$crlf."";
                $content	.= "#".$crlf."";
                $content	.= "# Table Structure for '".$table."' ".$crlf."";
                $content	.= "#".$crlf."";
                $content	.= $crlf;
                #################################################
                #	Build Table Structure			#
                #################################################
                /*	Table Structure	*/
                $schema_create = "";
                $schema_create .= "DROP TABLE IF EXISTS `".$table."`;".$crlf;
                $schema_create .= "CREATE TABLE `".$table."` (".$crlf;
                $result			= mysqli_query($mysqli, "SHOW FIELDS FROM `".$table."`") or die("Cant't show fields from '{$table}'" . mysqli_error($mysqli));
                while($row = mysqli_fetch_array($result))	{
                    $schema_create .= "   `$row[Field]` $row[Type]";
                    $schema_create .= ($row["Null"] != "YES")	? " NOT NULL" : "";
                    $schema_create .= (isset($row["Default"]) && (!empty($row["Default"]) || $row["Default"] == "0"))
                        ? " default '$row[Default]'" : "";
                    $schema_create .= ($row["Extra"] != "")		? " ".$row["Extra"] : "";
                    $schema_create .= ",".$crlf;
                }
                $schema_create		= preg_replace("/,".$crlf."$/", "", $schema_create);
                /*	Table Keys	*/
                $index		= array();
                $result		= mysqli_query($mysqli, "SHOW KEYS FROM `".$table."`") or die();
                while($row = mysqli_fetch_array($result))	{
                    if($row['Key_name'] == "PRIMARY")
                        $kname			= "PRIMARY KEY";
                    elseif($row['Non_unique'] == 0)
                        $kname			= "UNIQUE `".$row['Key_name']."`";
                    else
                        $kname			= "KEY `".$row['Key_name']."`";
                    if(!isset($index[$kname]))
                        $index[$kname] = array();
                    $index[$kname][]	= "`".$row['Column_name']."`".(isset($row['Sub_part']) ? "(".$row['Sub_part'].")" : "");
                }
                foreach($index as $x => $columns)	{
                    $schema_create .= ",".$crlf;
                    $schema_create .= "   ".$x." (" . implode($columns, ", ") . ")";
                }
                $schema_create .= $crlf.") ";
                #	DataBase Type								#
                $result		= mysqli_query($mysqli, "SHOW TABLE STATUS FROM ".$dbname." LIKE '".$table."'") or die();
                $row		= mysqli_fetch_array($result);
                $schema_create	.= (!empty($row['Type']) ? " TYPE=".$row['Type'] : "");
                $schema_create	.= (!empty($row['Auto_increment']) ? " AUTO_INCREMENT=".$row['Auto_increment'] : "");
                $schema_create	.= ";".$crlf.$crlf;
                $content	.= $schema_create;
                $schema_create	= "";
                #################################################
                #	Build Table Content (INSERT)		#
                #################################################
                $content	.= "#".$crlf."";
                $content	.= "# Dumping data for table '".$table."'".$crlf."";
                $content	.= "#$crlf";
                $content	.= $crlf;
                $result = mysqli_query($mysqli, "SELECT * FROM `$table`") or die();
                $a		= 0;
                while($row = mysqli_fetch_row($result))	{
                    $table_list = "(";
                    while($field = mysqli_fetch_field( $result )) {
                        $table_list .= "`" . $field->name . "`, ";
                    }
                    $table_list = substr($table_list,0,-2);
                    $table_list .= ")";
                    if(isset($GLOBALS["showcolumns"]))
                        $schema_insert .= "INSERT INTO `".$table."` ".$table_list." VALUES (";
                    else
                        $schema_insert .= "INSERT INTO `".$table."` VALUES (";
                    for($j=0; $j<mysqli_num_fields($result);$j++)	{
                        if(!isset($row[$j]))
                            $schema_insert .= " NULL,";
                        elseif($row[$j] != "")
                            $schema_insert .= " '".Str_Replace($search,$replace,addslashes($row[$j]))."',";
                        else
                            $schema_insert .= " '',";
                    }
                    $schema_insert = preg_replace("/,$/", "", $schema_insert);
                    $schema_insert .= ");".$crlf;
                    //$handler(trim($schema_insert));
                    $a++;
                }
                $content	.= $schema_insert."".$crlf."";
                $schema_insert	= "";
                $i++;
            }
            //echo $content;
            ##	Write to file	##
            fwrite($fp, $content);
            fclose($fp);
            $this ->  download($this -> filename) ;
        }
    }

    function download($filename)
    {

        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        //Use the switch-generated Content-Type
        header("Content-Type: application/force-download");
        //Force the download
        $header="Content-Disposition: attachment; filename=".$filename.";";
        header($header );
        header("Content-Transfer-Encoding: binary");
        header("Content-Length: ".filesize($filename));
        $handle = fopen($filename, "r");
        if(is_file($filename)){
            $contents = fread($handle, filesize($filename));
            fclose($handle);
            echo $contents;

        }
    }
}