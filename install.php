<?php
$tt = "";
$continue = true;
$success = false;

if (!is_writable("config/")) {
    @chmod("config/", 0777);
    if (!is_writable("config/")) {
        $continue = false;
        $tt = '<div class="form-group"><i class="fas fa-exclamation-triangle text-warning"></i><b>ERROR!</b> Please set chmod 755 or 777 for directory "config"</div>';
    }
}

if (!is_writable("backups/")) {
    @chmod("backups/", 0777);
    if (!is_writable("backups/")) {
        $continue = false;
        $tt = '<div class="form-group"><i class="fas fa-exclamation-triangle text-warning"></i><b>ERROR!</b> Please set chmod 755 or 777 for directory "backups"</div>';
    }
}

if ($continue && !empty($_REQUEST["install"]) && $_REQUEST['install'] == "yes") {

    $db_path = "config/database.sqlite";

    try {
        $pdo = new PDO('sqlite:' . $db_path);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->exec('PRAGMA journal_mode=WAL');
        $pdo->exec('PRAGMA foreign_keys=ON');

        // adm_clients
        $pdo->exec("CREATE TABLE IF NOT EXISTS adm_clients (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name VARCHAR(255),
            comment LONGTEXT,
            company VARCHAR(255),
            job_title VARCHAR(255),
            email VARCHAR(255),
            web VARCHAR(255),
            phone VARCHAR(255),
            address VARCHAR(255),
            dateCreated DATETIME DEFAULT NULL
        )");
        $tt .= '<div class="form-group"><i class="fas fa-check text-success"></i> Created table "adm_clients" (1/4)</div>';

        // adm_domains
        $pdo->exec("CREATE TABLE IF NOT EXISTS adm_domains (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            registrar TEXT,
            domain VARCHAR(255),
            comment LONGTEXT,
            clientID INTEGER,
            whoisreply LONGTEXT,
            renew_link TEXT,
            renewalDate DATE DEFAULT NULL,
            dateCreated DATETIME DEFAULT NULL,
            registrationDate DATE DEFAULT NULL
        )");
        $tt .= '<div class="form-group"><i class="fas fa-check text-success"></i> Created table "adm_domains" (2/4)</div>';

        // adm_settings
        $pdo->exec("CREATE TABLE IF NOT EXISTS adm_settings (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username VARCHAR(255),
            password VARCHAR(255),
            remember_token VARCHAR(64) NOT NULL DEFAULT '',
            adminEmail VARCHAR(255),
            adminLang VARCHAR(2),
            show_debug INTEGER DEFAULT 1,
            show_domdata INTEGER DEFAULT 1,
            mail_method VARCHAR(10) NOT NULL DEFAULT 'mail',
            smtp_host VARCHAR(255) NOT NULL DEFAULT '',
            smtp_port INTEGER NOT NULL DEFAULT 587,
            smtp_encryption VARCHAR(10) NOT NULL DEFAULT 'tls',
            smtp_user VARCHAR(255) NOT NULL DEFAULT '',
            smtp_pass VARCHAR(500) NOT NULL DEFAULT '',
            smtp_from_name VARCHAR(255) NOT NULL DEFAULT '',
            smtp_from_email VARCHAR(255) NOT NULL DEFAULT ''
        )");
        $tt .= '<div class="form-group"><i class="fas fa-check text-success"></i> Created table "adm_settings" (3/4)</div>';

        $stmt = $pdo->prepare("INSERT INTO adm_settings (username, password, remember_token, adminEmail, adminLang, mail_method, smtp_port, smtp_encryption) VALUES (?, ?, '', ?, ?, 'mail', 587, 'tls')");
        $stmt->execute(['admin', '1a1dc91c907325c69271ddf0c944bc72', 'demo@email.com', 'en']);
        $tt .= '<div class="form-group"><i class="fas fa-check text-success"></i> Default settings record created!</div>';

        // adm_data
        $pdo->exec("CREATE TABLE IF NOT EXISTS adm_data (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            type TINYINT DEFAULT 4,
            aa LONGTEXT,
            bb LONGTEXT,
            cc LONGTEXT,
            dd LONGTEXT,
            ee LONGTEXT,
            ff LONGTEXT
        )");
        $tt .= '<div class="form-group"><i class="fas fa-check text-success"></i> Created table "adm_data" (4/4)</div>';

        $tt .= '<hr><div class="form-group"><p class="text-primary"><strong><i class="far fa-thumbs-up fa-2x"></i> Installation successful!</strong></p>
            <p>Please <span style="color:red;">delete this file</span> now and go to your <a href="index.php">admin</a> page.</p></div>
            <div class="form-group"><p><i class="fas fa-user-lock text-info fa-2x"></i> Default username &amp; password:<br><b>admin / pass</b></p></div>
            <div class="form-group"><p>IMPORTANT: change CHMOD to 644 or 744 for directory "config"!</p></div>';
        $success = true;

        @chmod("config/", 0755);

    } catch (PDOException $e) {
        $continue = false;
        $tt .= '<div class="form-group"><i class="far fa-times-circle text-danger"></i> <b>ERROR!</b> ' . htmlspecialchars($e->getMessage()) . '</div>';
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
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="assets/js/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="assets/js/icheck-bootstrap/icheck-bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/adminlte.min.css">
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
        <img src="assets/img/domain.png" alt="Logo" class="brand-image" style="width:48px;"> <b style="color:#1E81CE;">Domain Reminder</b>
    </div>
    <div class="card">
        <div class="card-body login-card-body">
            <?php if (!empty($tt)) { echo $tt; } ?>
            <?php if (!$success) { ?>
            <form method="post" action="install.php" enctype="multipart/form-data" name="ff1">
                <div class="form-group">
                    <p>Domain Reminder will create a <strong>SQLite database</strong> automatically in the <code>config/</code> folder.</p>
                    <p>No database credentials are needed. Just click Install.</p>
                </div>
                <button type="submit" name="submit" value="Submit" class="btn btn-primary btn-block">Install</button>
                <input type="hidden" value="yes" name="install" />
            </form>
            <?php } ?>
        </div>
    </div>
</div>
<script src="assets/js/jquery/jquery.min.js"></script>
<script src="assets/js/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/adminlte.min.js"></script>
</body>
</html>
