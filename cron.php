<?php
    // error_reporting(E_ALL); ini_set("display_errors",1); // uncomment to debug
    require_once("includes/dbconnect.php");
    require_once("includes/functions.php");
    require_once("config/version.php");

    // Load language from DB dynamically — no hardcoded lang list needed
    $lang_row = $pdo->query("SELECT adminLang FROM adm_settings WHERE id = 1")->fetch();
    $adminLang = $lang_row ? ($lang_row['adminLang'] ?: 'en') : 'en';
    $adminLang = preg_replace('/[^a-z]/', '', $adminLang) ?: 'en';
    $lang_file = __DIR__ . '/langs/lang.' . $adminLang . '.php';
    if (!file_exists($lang_file)) {
        $lang_file = __DIR__ . '/langs/lang.en.php'; // fallback to English
    }
    if (file_exists($lang_file)) {
        include $lang_file;
    }
	
    // Validate token before doing anything
    $token_row   = $pdo->query("SELECT cron_token FROM adm_settings WHERE id = 1")->fetch(PDO::FETCH_ASSOC);
    $valid_token = $token_row ? $token_row['cron_token'] : '';
    $given_token = isset($_REQUEST['token']) ? trim($_REQUEST['token']) : '';

    if (empty($valid_token) || !hash_equals($valid_token, $given_token)) {
        // Invalid or missing token — return nothing, reveal nothing
        http_response_code(403);
        exit();
    }

    if (!empty($_REQUEST["cron"]) && $_REQUEST["cron"] == "do" && !empty($_REQUEST["d"])) {
        $days = (int)$_REQUEST["d"];

        $subject = $lang['DOMAIN_NOTICE'];
        $message = '<!doctype html><html><head><meta charset="UTF-8"><title>' . $lang['DOMAIN_NOTICE'] . '</title></head><body>';
        $message .= '<p>' . $lang['HI_THERE'] . '</p>';
        $message .= '<p>' . $lang['YOUR_LIST'] . ' <code>' . $days . '</code> ' . $lang['DAYS'] . ':</p>';
        $send = false;
        $date = date("Y-m-d", strtotime(date("Y-m-d") . "+" . $days . " days"));
        $stmt = $pdo->prepare("SELECT * FROM adm_domains WHERE renewalDate <= ? ORDER BY renewalDate ASC");
        $stmt->execute([$date]);
        $rows = $stmt->fetchAll();
        if (!empty($rows)) {
            $message .= '<ul>';
            $send = true;
            foreach ($rows as $rr) {
                $message .= '<li>';
                $message .= '<strong>' . $lang['DOMAIN_NAME'] . ': </strong><span style="color:#138496;">' . htmlspecialchars($rr["domain"]) . '</span><br>';
                $message .= '<strong>' . $lang['EXPERATION_DATE'] . ': </strong>' . date("d F Y", strtotime($rr["renewalDate"])) . '<br>';
                $message .= '<strong>' . $lang['CLIENT'] . ': </strong>' . htmlspecialchars(getClientName((int)$rr["clientID"])) . '<br>';
                $message .= '</li>';
            }
            $message .= '</ul>';
        }
        $message .= '</body></html>';

        // Build the resend link for the email (includes token so it works from inbox)
        $protocol   = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
        $base_url   = $protocol . '://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']);
        $resend_url = $base_url . '/cron.php?cron=do&d=' . $days . '&token=' . urlencode($valid_token);
        $message   .= '<p style="font-size:12px;color:#999;margin-top:20px;">'
                    . '<a href="' . htmlspecialchars($resend_url) . '">' . $lang['SEND_NOTICE'] . '</a></p>';

        if ($send) {
            require_once('includes/mailer.php');
            $admin_email = getAdminEmail();
            $result = send_mail($admin_email, $subject, $message);
            // Show brief result in popup then close
            if ($result === true) {
                echo '<p style="color:green;font-family:sans-serif;padding:20px;">
                        <strong>&#10003; ' . $lang['DOMAIN_NOTICE'] . '</strong><br>
                        ' . $lang['EMAIL_UPDATED'] . ': ' . htmlspecialchars($admin_email) . '
                      </p>';
            } else {
                echo '<p style="color:red;font-family:sans-serif;padding:20px;">Mail error: ' . htmlspecialchars($result) . '</p>';
            }
        } else {
            echo '<p style="font-family:sans-serif;padding:20px;">' . $lang['NO_DOMAINS_EXPIRING'] . '</p>';
        }
        echo '<script>setTimeout(function(){ window.close(); }, 2000);</script>';
    }
?>