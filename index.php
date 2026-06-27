<?php
error_reporting(E_ALL);
$secondsPerDay = 24 * 60 * 60;
$sessionLifetime = 90 * $secondsPerDay;
session_set_cookie_params($sessionLifetime);
ini_set('session.gc_maxlifetime', $sessionLifetime);
session_start();
require_once("includes/dbconnect.php");
require_once("includes/functions.php");
require_once("includes/languages.php");
require_once("config/version.php");
	
$msg = "";

// Remember Me — auto-login via cookie token
if (!isset($_SESSION['logged_in']) && isset($_COOKIE['remember_token'])) {
    $token = trim($_COOKIE['remember_token']);
    if ($token !== '') {
        $stmt = $pdo->prepare("SELECT * FROM adm_settings WHERE remember_token = ? LIMIT 1");
        $stmt->execute([$token]);
        $row = $stmt->fetch();
        if ($row) {
            $_SESSION['idUser'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['accesslevel'] = 1899;
            $_SESSION['logged_in'] = true;
            $newToken = bin2hex(random_bytes(32));
            $upd = $pdo->prepare("UPDATE adm_settings SET remember_token = ? WHERE id = ?");
            $upd->execute([$newToken, $row['id']]);
            setcookie('remember_token', $newToken, time() + $sessionLifetime, '/', '', false, true);
        } else {
            setcookie('remember_token', '', time() - 3600, '/');
        }
    }
}
$username = (!empty($_REQUEST['username'])) ? strip_tags(str_replace("'", "`", $_REQUEST['username'])) : '';
$password = (!empty($_REQUEST['password'])) ? strip_tags(str_replace("'", "`", $_REQUEST['password'])) : '';
if (!empty($_REQUEST["login"]) && $_REQUEST['login'] == "yes") {
    if ($username == "" || $password == "") {
        $msg = '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><h5><i class="icon fas fa-exclamation-triangle"></i> '.$lang['ALERT'].'</h5>'.$lang['WRONG_LOGIN'].'</div>';
    } else {
        $stmt = $pdo->prepare("SELECT * FROM adm_settings WHERE username = ?");
        $stmt->execute([$username]);
        $row = $stmt->fetch();
        if ($row && md5($password) === $row['password']) {
            $_SESSION['idUser'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['accesslevel'] = 1899;
            $_SESSION['logged_in'] = true;
            if (!empty($_REQUEST['remember_me'])) {
                $rememberToken = bin2hex(random_bytes(32));
                $upd = $pdo->prepare("UPDATE adm_settings SET remember_token = ? WHERE id = ?");
                $upd->execute([$rememberToken, $row['id']]);
                setcookie('remember_token', $rememberToken, time() + $sessionLifetime, '/', '', false, true);
            }
        } else {
            $msg = '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><h5><i class="icon fas fa-exclamation-triangle"></i> '.$lang['ALERT'].'</h5>'.$lang['WRONG_LOGIN'].'</div>';
        }
    }
}
if (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] == true) {
    header("Location: dashboard.php"); exit();
}
?>
<!DOCTYPE html>
<html lang="<?php echo $lang['LANG_CODE']; ?>">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Log in | <?php echo $lang['SITE_TITLE']; ?></title>
    <link rel="icon" type="image/png" sizes="96x96" href="assets/img/domain.png">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="assets/js/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="assets/js/icheck-bootstrap/icheck-bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/adminlte.min.css">
    <link rel="stylesheet" href="assets/css/custome.css">
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo"><img src="assets/img/domain.png" alt="Logo" class="brand-image" style="width:48px;"> <b style="color:#1E81CE;"><?php echo $lang['SITE_TITLE']; ?></b></div>
    <div class="card"><div class="card-body login-card-body">
        <?php echo $msg; ?>
        <p class="login-box-msg"><?php echo $lang['SIGNIN_TO_START']; ?></p>
        <form method="post" action="index.php" enctype="multipart/form-data" name="ff1" id="login-form">
            <div class="input-group mb-3">
                <input type="text" id="username" name="username" placeholder="<?php echo $lang['USERNAME']; ?>" class="form-control" autofocus>
                <div class="input-group-append"><div class="input-group-text"><span class="fas fa-user"></span></div></div>
            </div>
            <div class="input-group mb-3">
                <input type="password" id="password" name="password" class="form-control" placeholder="<?php echo $lang['PASSWORD']; ?>">
                <div class="input-group-append"><div class="input-group-text"><span class="fas fa-lock"></span></div></div>
            </div>
            <div class="row">
                <div class="col-8">
                    <div class="icheck-primary">
                        <input type="checkbox" id="savelogin" name="remember_me">
                        <label for="savelogin"><?php echo $lang['REMEMBER_ME']; ?></label>
                    </div>
                </div>
                <div class="col-4">
                    <button type="submit" value="Submit" class="btn btn-primary btn-block"><?php echo $lang['SIGNIN']; ?></button>
                    <input type="hidden" value="yes" name="login" />
                </div>
            </div>
        </form>
    </div></div>
</div>
<script src="assets/js/jquery/jquery.min.js"></script>
<script src="assets/js/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/adminlte.min.js"></script>
<script src="assets/js/setcookie.js"></script>
</body></html>
