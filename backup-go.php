<?php
    session_start();
    include("includes/dbconnect.php");
    require_once("includes/functions.php");
    if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {
        header("Location: index.php"); exit();
    }
    $action = (!empty($_REQUEST["action"])) ? strip_tags(str_replace("'","`",$_REQUEST["action"])) : '';
    if (!empty($action) && $action == "backup") {
        $db_path = __DIR__ . '/config/database.sqlite';
            $date = date("Y-m-d-H-i-s");
            $backup_file = "backups/adm_backup_" . $date . ".sqlite";
            if (copy($db_path, $backup_file)) {
                // Offer the file as a download
                header("Pragma: public");
                header("Expires: 0");
                header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
                header("Content-Description: File Transfer");
                header("Content-Type: application/octet-stream");
                header("Content-Disposition: attachment; filename=adm_backup_" . $date . ".sqlite");
                header("Content-Transfer-Encoding: binary");
                header("Content-Length: " . filesize($backup_file));
                readfile($backup_file);
            } else {
                echo "Error: could not create backup file. Check that backups/ is writable.";
            }
    }
?>