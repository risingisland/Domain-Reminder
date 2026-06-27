<?php
session_start();

require_once("includes/dbconnect.php");

// Clear remember_token from DB and cookie
if (isset($_SESSION['idUser'])) {
    $id = (int)$_SESSION['idUser'];
    $stmt = $pdo->prepare("UPDATE adm_settings SET remember_token = '' WHERE id = ?");
    $stmt->execute([$id]);
}
setcookie('remember_token', '', time() - 3600, '/');

session_unset();
session_destroy();

header("Location: index.php");
exit();
