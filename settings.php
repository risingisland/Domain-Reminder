<?php
    session_start();
    require_once("includes/dbconnect.php");
    require_once("includes/functions.php");
    require_once("includes/languages.php");
	require_once("config/version.php");
	
    $msg = "";
	
    if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {
        header("Location: index.php"); exit();
    }
    $username    = (!empty($_REQUEST["username"]))    ? strip_tags(str_replace("'","`",$_REQUEST["username"]))    : '';
    $adminEmail  = (!empty($_REQUEST["adminEmail"]))  ? strip_tags(str_replace("'","`",$_REQUEST["adminEmail"]))  : '';
    $show_debug  = (!empty($_REQUEST["show_debug"]))  ? strip_tags(str_replace("'","`",$_REQUEST["show_debug"]))  : '';
    $new_pass    = (!empty($_REQUEST["new_pass"]))    ? strip_tags(str_replace("'","`",$_REQUEST["new_pass"]))    : '';
    $new_pass2   = (!empty($_REQUEST["new_pass2"]))   ? strip_tags(str_replace("'","`",$_REQUEST["new_pass2"]))   : '';
    $adminLang   = (!empty($_REQUEST["adminLang"]))   ? strip_tags(str_replace("'","`",$_REQUEST["adminLang"]))   : '';
    $show_domdata   = (!empty($_REQUEST["show_domdata"]))   ? strip_tags(str_replace("'","`",$_REQUEST["show_domdata"]))   : '';
    $mail_method    = (!empty($_REQUEST["mail_method"]))    ? strip_tags($_REQUEST["mail_method"])    : '';
    $smtp_host      = (!empty($_REQUEST["smtp_host"]))      ? strip_tags($_REQUEST["smtp_host"])      : '';
    $smtp_port      = (!empty($_REQUEST["smtp_port"]))      ? (int)$_REQUEST["smtp_port"]             : 0;
    $smtp_encryption= (!empty($_REQUEST["smtp_encryption"]))? strip_tags($_REQUEST["smtp_encryption"]): '';
    $smtp_user      = (!empty($_REQUEST["smtp_user"]))      ? strip_tags($_REQUEST["smtp_user"])      : '';
    $smtp_pass_raw  = isset($_REQUEST["smtp_pass"])         ? $_REQUEST["smtp_pass"]                  : null;
    $smtp_from_name = (!empty($_REQUEST["smtp_from_name"])) ? strip_tags($_REQUEST["smtp_from_name"]) : '';
    $smtp_from_email= (!empty($_REQUEST["smtp_from_email"]))? strip_tags($_REQUEST["smtp_from_email"]): '';

    if (!empty($_REQUEST["edit_settings"]) && $_REQUEST["edit_settings"] == "yes") {
        $saved = [];
        $warnings = [];

        if (!empty($username)) {
            $stmt = $pdo->prepare("UPDATE adm_settings SET username = ?, show_debug = ? WHERE id = 1");
            $stmt->execute([$username, $show_debug]);
            $saved[] = 'USERNAME_UPDATED';
        }
        if (!empty($adminEmail)) {
            $stmt = $pdo->prepare("UPDATE adm_settings SET adminEmail = ?, show_debug = ? WHERE id = 1");
            $stmt->execute([$adminEmail, $show_debug]);
            $saved[] = 'EMAIL_UPDATED';
        }
        $lang_form = (!empty($_REQUEST["lang-form"])) ? strip_tags($_REQUEST["lang-form"]) : '';
        if (!empty($lang_form) && in_array($lang_form, ['en','es','pl'])) {
            $stmt = $pdo->prepare("UPDATE adm_settings SET adminLang = ? WHERE id = 1");
            $stmt->execute([$lang_form]);
            $_SESSION['lang'] = $lang_form;
            setcookie('lang', $lang_form, time() + (3600 * 24 * 30));
            $saved[] = 'CRON_LANG_UPDATED';
        }
        if (!empty($show_domdata)) {
            $stmt = $pdo->prepare("UPDATE adm_settings SET show_domdata = ?, show_debug = ? WHERE id = 1");
            $stmt->execute([$show_domdata, $show_debug]);
            $saved[] = 'SHOW_DOMAIN_DATA_UPDATED';
        }
        // Save SMTP / mail settings
        if (!empty($mail_method) && in_array($mail_method, ['mail', 'smtp'])) {
            require_once('includes/mailer.php');
            $pass_to_save = null;
            if ($smtp_pass_raw !== null && $smtp_pass_raw !== '' && $smtp_pass_raw !== '••••••••') {
                $pass_to_save = smtp_pass_encrypt($smtp_pass_raw);
            }
            if ($pass_to_save !== null) {
                $stmt = $pdo->prepare("UPDATE adm_settings SET mail_method=?, smtp_host=?, smtp_port=?, smtp_encryption=?, smtp_user=?, smtp_pass=?, smtp_from_name=?, smtp_from_email=? WHERE id=1");
                $stmt->execute([$mail_method, $smtp_host, $smtp_port ?: 587, $smtp_encryption, $smtp_user, $pass_to_save, $smtp_from_name, $smtp_from_email]);
            } else {
                $stmt = $pdo->prepare("UPDATE adm_settings SET mail_method=?, smtp_host=?, smtp_port=?, smtp_encryption=?, smtp_user=?, smtp_from_name=?, smtp_from_email=? WHERE id=1");
                $stmt->execute([$mail_method, $smtp_host, $smtp_port ?: 587, $smtp_encryption, $smtp_user, $smtp_from_name, $smtp_from_email]);
            }
            $saved[] = 'MAIL_SETTINGS_SAVED';
        }

        if (!empty($new_pass) && !empty($new_pass2)) {
            if (md5($new_pass) === md5($new_pass2)) {
                $stmt = $pdo->prepare("UPDATE adm_settings SET password = ? WHERE id = 1");
                $stmt->execute([md5($new_pass)]);
                $saved[] = 'PASSWORD_UPDATED';
            } else {
                $warnings[] = 'PASSWORD_NOT_MATCH';
            }
        }
        // Store keys in session and redirect — messages are built after redirect in new language
        $_SESSION['settings_saved']    = $saved;
        $_SESSION['settings_warnings'] = $warnings;
        header("Location: settings.php");
        exit();
    }
    // Build flash messages after redirect using the now-correct $lang
    if (!empty($_SESSION['settings_saved'])) {
        foreach ($_SESSION['settings_saved'] as $key) {
            $text = ($key === 'MAIL_SETTINGS_SAVED') ? 'Mail settings saved.' : ($lang[$key] ?? $key);
            $msg .= '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><h5><i class="icon fas fa-check"></i> ' . $text . '</h5></div>';
        }
        unset($_SESSION['settings_saved']);
    }
    if (!empty($_SESSION['settings_warnings'])) {
        foreach ($_SESSION['settings_warnings'] as $key) {
            $text = $lang[$key] ?? $key;
            $msg .= '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><h5><i class="icon fas fa-exclamation-triangle"></i> ' . $text . '</h5></div>';
        }
        unset($_SESSION['settings_warnings']);
    }
    $stmt = $pdo->query("SELECT id,username,adminEmail,adminLang,show_debug,show_domdata,mail_method,smtp_host,smtp_port,smtp_encryption,smtp_user,smtp_pass,smtp_from_name,smtp_from_email FROM adm_settings WHERE id = 1");
    $row = $stmt->fetch();
    if ($row) { foreach ($row as $key => $value) { $$key = $value; } }
?>
<!DOCTYPE html>
<html lang="<?php echo $lang['LANG_CODE']; ?>">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $lang['SITE_TITLE']; ?></title>
    <link rel="icon" type="image/png" sizes="96x96" href="assets/img/domain.png">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="assets/js/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="assets/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini">
	<div class="wrapper">
			<!-- Navbar -->
			<nav class="main-header navbar navbar-expand navbar-white navbar-light">
				<ul class="navbar-nav">
					<li class="nav-item"> <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a> </li>
				</ul>
				<ul class="navbar-nav ml-auto">
					<li class="nav-item dropdown">
						<a class="nav-link" data-toggle="dropdown" href="#"><i class="fas fa-user-circle fa-2x text-primary"></i></a>
						<div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
							<span class="dropdown-header"><?php echo $lang['ADMIN']; ?></span>
							<div class="dropdown-divider"></div>
							<a href="settings.php" class="dropdown-item"><i class="fas fa-cogs mr-2 text-info"></i> <?php echo $lang['SETTINGS']; ?></a>
							<div class="dropdown-divider"></div>
							<a href="logout.php" class="dropdown-item"><i class="fas fa-sign-out-alt mr-2 text-danger"></i> <?php echo $lang['SIGNOUT']; ?></a>
						</div>
					</li>
				</ul>
			</nav>
			<!-- /.navbar -->
			<!-- Main Sidebar Container -->
			<aside class="main-sidebar sidebar-dark-primary elevation-4">
				<a href="dashboard.php" class="brand-link"> <img src="assets/img/domain.png" alt="Logo" class="brand-image"> <span class="brand-text" style="color:#1E81CE;"><b><?php echo $lang['SITE_TITLE']; ?></b></span> </a>
				<div class="sidebar">
					<div class="user-panel mt-3 pb-3 mb-3 d-flex"><div>&nbsp;</div></div>
					<nav class="mt-2">
						<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
							<li class="nav-item"><a href="dashboard.php" class="nav-link"><i class="nav-icon fas fa-tachometer-alt"></i><p> <?php echo $lang['DASHBOARD']; ?> </p></a></li>
							<li class="nav-item"><a href="domains.php" class="nav-link"><i class="nav-icon fas fa-globe"></i><p> <?php echo $lang['DOMAINS']; ?> </p></a></li>
							<li class="nav-item"><a href="domains-expiring.php" class="nav-link"><i class="nav-icon fas fa-flag"></i><p> <?php echo $lang['EXPIRING_SOON']; ?> <span class="right badge badge-warning"><?php echo checkHowManyExpires();?></span></p></a></li>
							<li class="nav-item"><a href="clients.php" class="nav-link"><i class="nav-icon fas fa-users"></i><p> <?php echo $lang['CLIENTS']; ?> </p></a></li>
							<li class="nav-item"><a href="clients-add.php" class="nav-link"><i class="nav-icon fas fa-user-plus"></i><p> <?php echo $lang['ADD_CLIENT']; ?> </p></a></li>
							<li class="nav-item"><a href="backup.php" class="nav-link"><i class="nav-icon fas fa-database"></i><p> <?php echo $lang['DATABASES']; ?> </p></a></li>
							<li class="nav-item"><a href="help.php" class="nav-link"><i class="nav-icon fas fa-question-circle"></i><p> <?php echo $lang['HELP']; ?> </p></a></li>
							<li class="nav-item"><a href="settings.php" class="nav-link active"><i class="nav-icon fas fa-cogs"></i><p> <?php echo $lang['SETTINGS']; ?> </p></a></li>
						</ul>
					</nav>
				</div>
			</aside>
			<!-- Content Wrapper -->
			<div class="content-wrapper">
    <div class="content-header"><div class="container-fluid"><div class="row mb-2"><div class="col-sm-12">
        <h1 class="m-0 text-primary"><i class="fas fa-cogs"></i> <?php echo $lang['SETTINGS']; ?></h1>
    </div></div></div></div>
    <div class="content"><div class="container-fluid"><div class="row">
        <div class="col-md-6"><?php echo $msg; ?></div>
        <div class="col-12">
            <div class="card card-primary card-outline card-tabs">
                <div class="card-header p-0 pt-1 border-bottom-0">
                    <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                        <li class="nav-item"><a class="nav-link active" data-toggle="pill" href="#tab-admin" role="tab"><strong><i class="fas fa-user text-primary"></i> <?php echo $lang['ADMIN']; ?></strong></a></li>
                        <li class="nav-item"><a class="nav-link" data-toggle="pill" href="#tab-email" role="tab"><strong><i class="fas fa-envelope"></i> Email</strong></a></li>
                    </ul>
                </div>
                <div class="card-body"><div class="tab-content">
                    <div class="tab-pane fade show active" id="tab-admin" role="tabpanel">
                        <form action="settings.php" enctype="multipart/form-data" method="post" name="ff1" class="form-horizontal">
                            <div class="card-body">
                                <div class="form-group row col-sm-6">
                                    <label><?php echo $lang['USERNAME']; ?>:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-user text-primary"></i></span></div>
                                        <input type="text" class="form-control" name="username" value="<?php echo htmlspecialchars($username ?? ''); ?>">
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group row col-sm-6">
                                    <label><?php echo $lang['NEW_PASSWORD']; ?>:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-key text-primary"></i></span></div>
                                        <input type="password" class="form-control" name="new_pass" placeholder="<?php echo $lang['PASSWORD']; ?>">
                                    </div>
                                </div>
                                <div class="form-group row col-sm-6">
                                    <label></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-check-double text-primary"></i></span></div>
                                        <input type="password" class="form-control" name="new_pass2" placeholder="<?php echo $lang['CONFIRM']; ?>">
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group row col-sm-6">
                                    <label><?php echo $lang['EMAIL_FOR_CRON']; ?>:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-envelope text-primary"></i></span></div>
                                        <input type="text" class="form-control" name="adminEmail" value="<?php echo htmlspecialchars($adminEmail ?? ''); ?>">
                                    </div>
                                </div>
                                <hr>
                                
                                <hr>
                                <div class="form-group row col-sm-6">
                                    <label><?php echo $lang['SHOW_WHOIS']; ?></label>
                                </div>
                                <div class="form-group row col-sm-6">
                                    <div class="col-sm-2 offset-md-1">
                                        <input type="radio" class="form-check-input" name="show_debug" value="1" <?php if(($show_debug ?? '') == "1"){ echo "checked"; }?>> <?php echo $lang['YES']; ?>
                                    </div>
                                    <div class="col-sm-2">
                                        <input type="radio" class="form-check-input" name="show_debug" value="2" <?php if(($show_debug ?? '') == "2"){ echo "checked"; }?>> <?php echo $lang['NO']; ?>
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group row col-sm-6">
                                    <label><?php echo $lang['SHOW_DOMAIN_DATA']; ?></label>
                                </div>
                                <div class="form-group row col-sm-6">
                                    <div class="col-sm-2 offset-md-1">
                                        <input type="radio" class="form-check-input" name="show_domdata" value="1" <?php if(($show_domdata ?? '') == "1"){ echo "checked"; }?>> <?php echo $lang['YES']; ?>
                                    </div>
                                    <div class="col-sm-2">
                                        <input type="radio" class="form-check-input" name="show_domdata" value="2" <?php if(($show_domdata ?? '') == "2"){ echo "checked"; }?>> <?php echo $lang['NO']; ?>
                                    </div>
                                </div>
                            </div>
                                <hr>
                                <div class="form-group col-sm-6">
                                    <label><i class="fas fa-language text-primary"></i> <?php echo $lang['CHOOSE_YOUR_LANG']; ?>:</label>
                                    <select class="form-control" name="lang-form">
                                        <option value=""> -- <?php echo $lang['LANG_LONG']; ?> -- </option>
                                        <?php
                                        $langs_dir = __DIR__ . '/langs/';
                                        $lang_files = glob($langs_dir . 'lang.*.php');
                                        if ($lang_files) {
                                            sort($lang_files);
                                            foreach ($lang_files as $_lf) {
                                                $_tmp = (function() use ($_lf) {
                                                    $lang = []; include $_lf; return $lang;
                                                })();
                                                if (!empty($_tmp['LANG_CODE']) && !empty($_tmp['LANG_LONG'])) {
                                                    $selected = (($adminLang ?? 'en') === $_tmp['LANG_CODE']) ? 'selected' : '';
                                                    echo '<option value="' . htmlspecialchars($_tmp['LANG_CODE']) . '" ' . $selected . '>' . htmlspecialchars($_tmp['LANG_LONG']) . '</option>';
                                                }
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            <div class="card-footer col-sm-6">
                                <button type="submit" class="btn btn-primary float-right"><?php echo $lang['UPDATE']; ?></button>
                                <input value="yes" name="edit_settings" type="hidden" />
                                <input value="<?php echo $id ?? ''; ?>" name="id" type="hidden" />
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane fade" id="tab-email" role="tabpanel">
                        <form action="settings.php" enctype="multipart/form-data" method="post" name="ff_email" class="form-horizontal">
                            <div class="card-body">

                                <h6 class="text-muted mb-3"><i class="fas fa-paper-plane text-primary"></i> Mail Method</h6>
                                <div class="form-group row col-sm-8">
                                    <div class="col-sm-3">
                                        <div class="icheck-primary">
                                            <input type="radio" id="method_mail" name="mail_method" value="mail" <?php echo (($mail_method ?? 'mail') === 'mail') ? 'checked' : ''; ?>>
                                            <label for="method_mail">PHP mail()</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="icheck-primary">
                                            <input type="radio" id="method_smtp" name="mail_method" value="smtp" <?php echo (($mail_method ?? '') === 'smtp') ? 'checked' : ''; ?>>
                                            <label for="method_smtp">SMTP</label>
                                        </div>
                                    </div>
                                </div>

                                <div id="smtp-section">
                                <hr>
                                <h6 class="text-muted mb-3"><i class="fas fa-user-circle text-primary"></i> From Details</h6>

                                <div class="form-group row col-sm-8">
                                    <label class="col-sm-3 col-form-label">From Name:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="smtp_from_name" value="<?php echo htmlspecialchars($smtp_from_name ?? ''); ?>" placeholder="Domain Reminder">
                                    </div>
                                </div>
                                <div class="form-group row col-sm-8">
                                    <label class="col-sm-3 col-form-label">From Email:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="smtp_from_email" value="<?php echo htmlspecialchars($smtp_from_email ?? ''); ?>" placeholder="noreply@yourdomain.com">
                                    </div>
                                </div>

                                <hr>
                                <h6 class="text-muted mb-3"><i class="fas fa-server text-primary"></i> SMTP Settings</h6>
                                <p class="text-muted small">Only required when using SMTP method above.</p>

                                <div class="form-group row col-sm-8">
                                    <label class="col-sm-3 col-form-label">Host:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="smtp_host" value="<?php echo htmlspecialchars($smtp_host ?? ''); ?>" placeholder="smtp.gmail.com">
                                    </div>
                                </div>
                                <div class="form-group row col-sm-8">
                                    <label class="col-sm-3 col-form-label">Port:</label>
                                    <div class="col-sm-5">
                                        <input type="number" class="form-control" name="smtp_port" value="<?php echo (int)($smtp_port ?? 587); ?>" placeholder="587">
                                        <small class="text-muted">587 = TLS &nbsp;|&nbsp; 465 = SSL &nbsp;|&nbsp; 25 = none</small>
                                    </div>
                                </div>
                                <div class="form-group row col-sm-8">
                                    <label class="col-sm-3 col-form-label">Encryption:</label>
                                    <div class="col-sm-5">
                                        <select class="form-control" name="smtp_encryption">
                                            <option value="tls"  <?php echo (($smtp_encryption ?? 'tls') === 'tls')  ? 'selected' : ''; ?>>TLS (STARTTLS)</option>
                                            <option value="ssl"  <?php echo (($smtp_encryption ?? '') === 'ssl')  ? 'selected' : ''; ?>>SSL</option>
                                            <option value="none" <?php echo (($smtp_encryption ?? '') === 'none') ? 'selected' : ''; ?>>None</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row col-sm-8">
                                    <label class="col-sm-3 col-form-label">Username:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="smtp_user" value="<?php echo htmlspecialchars($smtp_user ?? ''); ?>" placeholder="you@gmail.com" autocomplete="off">
                                    </div>
                                </div>
                                <div class="form-group row col-sm-8">
                                    <label class="col-sm-3 col-form-label">Password:</label>
                                    <div class="col-sm-9">
                                        <input type="password" class="form-control" name="smtp_pass"
                                            value="<?php echo (!empty($smtp_pass)) ? '••••••••' : ''; ?>"
                                            placeholder="Leave blank to keep current"
                                            autocomplete="new-password">
                                        <small class="text-muted">Stored encrypted. Leave blank to keep the existing password.</small>
                                    </div>
                                </div>
                                </div><!-- /#smtp-section -->

                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary float-right">Save Mail Settings</button>
                                <input value="yes" name="edit_settings" type="hidden" />
                            </div>
                        </form>

                        <hr>
                        <div class="card-body">
                            <h6 class="text-muted mb-3"><i class="fas fa-vial text-primary"></i> Test Email</h6>
                            <p class="text-muted small">Sends a test email to the Admin Email address set in the Admin tab.</p>
                            <div id="smtp-test-result" class="mb-3" style="display:none;"></div>
                            <button type="button" class="btn btn-outline-secondary mr-2" onclick="testMail('mail')">
                                <i class="fas fa-paper-plane"></i> Test mail()
                            </button>
                            <span id="btn-test-smtp">
                                <button type="button" class="btn btn-outline-primary" onclick="testMail('smtp')">
                                    <i class="fas fa-paper-plane"></i> Test SMTP
                                </button>
                            </span>
                        </div>
                    </div></div>
            </div>
        </div>
    </div></div></div>
</div>
<footer class="main-footer">
    <div class="float-right d-none d-sm-block"><p><b><?php echo $lang['VERSION']; ?>:</b> <?php echo $version; ?></p></div>
    <p><strong>Copyright &copy; <?php echo date("Y"); ?> <?php echo $lang['FOOTER_CREDITS']; ?></p>
</footer>
</div>
<script src="assets/js/jquery/jquery.min.js"></script>
<script src="assets/js/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/adminlte.min.js"></script>
<script>
function toggleSmtpSection() {
    var isSMTP = document.getElementById('method_smtp').checked;
    document.getElementById('smtp-section').style.display  = isSMTP ? '' : 'none';
    document.getElementById('btn-test-smtp').style.display = isSMTP ? '' : 'none';
}

// Set initial state on page load
document.addEventListener('DOMContentLoaded', function() {
    toggleSmtpSection();
    document.getElementById('method_mail').addEventListener('change', toggleSmtpSection);
    document.getElementById('method_smtp').addEventListener('change', toggleSmtpSection);
});

function testMail(method) {
    var btn = event.target.closest('button');
    var orig = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
    var result = document.getElementById('smtp-test-result');
    result.style.display = 'none';
    fetch('smtp-test.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'method=' + encodeURIComponent(method)
    })
    .then(function(r){ return r.json(); })
    .then(function(data) {
        result.style.display = 'block';
        result.className = data.ok
            ? 'alert alert-success'
            : 'alert alert-warning';
        result.innerHTML = '<i class="fas fa-' + (data.ok ? 'check' : 'exclamation-triangle') + '"></i> ' + data.msg;
        btn.disabled = false;
        btn.innerHTML = orig;
    })
    .catch(function(err) {
        result.style.display = 'block';
        result.className = 'alert alert-danger';
        result.innerHTML = '<i class="fas fa-times"></i> Request failed: ' + err;
        btn.disabled = false;
        btn.innerHTML = orig;
    });
}
</script>
</body></html>