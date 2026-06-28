<?php
/**
 * delete-install-file.php
 * Deletes one of the allowed install/reset scripts after confirmation.
 * Only accessible to logged-in admins.
 */

session_start();
require_once('includes/dbconnect.php');
require_once('includes/functions.php');
require_once('includes/languages.php');
require_once('config/version.php');

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: index.php'); exit();
}

// Whitelist — only these files may be deleted via this script
$allowed = [
    'install.php',
    '-install-default-admin.php',
    '-install-demo-data.php',
    'run-migration.php',
    'run-smtp-migration.php',
    'mysql-to-sql-export.php',
    'import-from-sql.php',
    'csv-export.php',
    'csv-import.php',
];

$file = isset($_GET['file']) ? basename($_GET['file']) : '';

if (!in_array($file, $allowed, true)) {
    header('Location: dashboard.php'); exit();
}

$full_path = __DIR__ . '/' . $file;

if (!file_exists($full_path)) {
    header('Location: dashboard.php'); exit();
}

if (isset($_GET['confirm']) && $_GET['confirm'] === '1') {
    unlink($full_path);
    header('Location: dashboard.php'); exit();
}
?>
<!DOCTYPE html>
<html lang="<?php echo $lang['LANG_CODE']; ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $lang['SITE_TITLE']; ?></title>
    <link rel="icon" type="image/png" sizes="96x96" href="assets/img/domain.png">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="assets/js/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="assets/css/adminlte.min.css">
    <link rel="stylesheet" href="assets/css/custome.css">
</head>
<body class="hold-transition login-page">
<div class="login-box" style="max-width:500px;">
    <div class="login-logo">
        <img src="assets/img/domain.png" alt="Logo" style="width:48px;">
        <b style="color:#1E81CE;"><?php echo $lang['SITE_TITLE']; ?></b>
    </div>
    <div class="card">
        <div class="card-body login-card-body">
            <div class="alert alert-danger">
                <h5><i class="fas fa-exclamation-triangle"></i> <?php echo $lang['INSTALL_FILE_DELETE_CONFIRM']; ?></h5>
                <p><code style="color:var(--yellow)"><?php echo htmlspecialchars($file); ?></code></p>
            </div>
            <a href="delete-install-file.php?file=<?php echo urlencode($file); ?>&confirm=1"
               class="btn btn-danger btn-block">
                <i class="fas fa-trash"></i> <?php echo $lang['DELETE']; ?>
            </a>
            <a href="dashboard.php" class="btn btn-secondary btn-block mt-2">
                <i class="fas fa-times"></i> <?php echo $lang['CANCEL'] ?? 'Cancel'; ?>
            </a>
        </div>
    </div>
</div>
<script src="assets/js/jquery/jquery.min.js"></script>
<script src="assets/js/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/adminlte.min.js"></script>
</body>
</html>
