<?php
/**
 * smtp-test.php
 * AJAX endpoint — tests mail() or SMTP and returns JSON.
 * Only accessible to logged-in admins.
 */

session_start();
require_once('includes/dbconnect.php');
require_once('includes/functions.php');
require_once('includes/mailer.php');

header('Content-Type: application/json');

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    echo json_encode(['ok' => false, 'msg' => 'Not authorised.']);
    exit();
}

$method = isset($_POST['method']) ? trim($_POST['method']) : '';
if (!in_array($method, ['mail', 'smtp'], true)) {
    echo json_encode(['ok' => false, 'msg' => 'Invalid method.']);
    exit();
}

// Load recipient from adm_settings
$stmt = $pdo->query("SELECT adminEmail FROM adm_settings WHERE id = 1");
$cfg  = $stmt->fetch(PDO::FETCH_ASSOC);
$to   = $cfg['adminEmail'] ?? '';

if (empty($to)) {
    echo json_encode(['ok' => false, 'msg' => 'No admin email address set. Save one in the Admin tab first.']);
    exit();
}

// If testing SMTP, temporarily override the saved method with what was posted,
// so we can test SMTP even if mail() is currently the active method.
// We do this by directly calling PHPMailer rather than going through send_mail().
if ($method === 'smtp') {

    if (!file_exists(__DIR__ . '/phpmailer/PHPMailer.php')) {
        echo json_encode(['ok' => false, 'msg' => 'PHPMailer not found at /phpmailer/PHPMailer.php']);
        exit();
    }

    require_once __DIR__ . '/phpmailer/Exception.php';
    require_once __DIR__ . '/phpmailer/PHPMailer.php';
    require_once __DIR__ . '/phpmailer/SMTP.php';

    $cfg_stmt = $pdo->query(
        "SELECT smtp_host, smtp_port, smtp_encryption, smtp_user, smtp_pass,
                smtp_from_name, smtp_from_email
         FROM adm_settings WHERE id = 1"
    );
    $s = $cfg_stmt->fetch(PDO::FETCH_ASSOC);

    if (empty($s['smtp_host'])) {
        echo json_encode(['ok' => false, 'msg' => 'SMTP host is not configured. Save your SMTP settings first.']);
        exit();
    }

    $mail = new PHPMailer\PHPMailer\PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = $s['smtp_host'];
        $mail->Port       = (int)($s['smtp_port'] ?: 587);
        $mail->SMTPAuth   = !empty($s['smtp_user']);
        $mail->Username   = $s['smtp_user'];
        $mail->Password   = smtp_pass_decrypt($s['smtp_pass']);
        $mail->SMTPSecure = match($s['smtp_encryption']) {
            'ssl'  => PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS,
            'tls'  => PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS,
            default => '',
        };
        $from_email = $s['smtp_from_email'] ?: $to;
        $from_name  = $s['smtp_from_name']  ?: 'Domain Reminder';
        $mail->setFrom($from_email, $from_name);
        $mail->addAddress($to);
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Subject = 'Domain Reminder — SMTP Test';
        $mail->Body    = '<p>This is a test email sent via <strong>SMTP</strong> from Domain Reminder.</p>'
                       . '<p>If you received this, your SMTP configuration is working correctly.</p>';
        $mail->send();
        echo json_encode(['ok' => true, 'msg' => 'SMTP test email sent successfully to <strong>' . htmlspecialchars($to) . '</strong>.']);
    } catch (PHPMailer\PHPMailer\Exception $e) {
        echo json_encode(['ok' => false, 'msg' => 'SMTP error: ' . htmlspecialchars($mail->ErrorInfo)]);
    }

} else {
    // Test mail()
    $headers  = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=utf-8\r\n";
    $headers .= "From: Domain Reminder <noreply@" . preg_replace('/^www\./', '', $_SERVER['HTTP_HOST']) . ">\r\n";
    $body     = '<p>This is a test email sent via <strong>PHP mail()</strong> from Domain Reminder.</p>'
              . '<p>If you received this, your server mail() function is working correctly.</p>';
    $ok = mail($to, 'Domain Reminder — mail() Test', $body, $headers);
    if ($ok) {
        echo json_encode(['ok' => true, 'msg' => 'mail() test sent to <strong>' . htmlspecialchars($to) . '</strong>. Check your inbox (and spam folder).']);
    } else {
        echo json_encode(['ok' => false, 'msg' => 'mail() returned false. Your server may not have a mail transport configured.']);
    }
}
