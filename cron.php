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
        } else {
            echo '<li>' . $lang['NO_DOMAINS_EXPIRING'] . '</li>';
        }
        $message .= '</body></html>';
        if ($send) {
            require_once('includes/mailer.php');
            $admin_email = getAdminEmail();
            $result = send_mail($admin_email, $subject, $message);
            if ($result !== true) {
                echo '<p style="color:red;">Mail error: ' . htmlspecialchars($result) . '</p>';
            }
            print $message;
        }
    }
?>