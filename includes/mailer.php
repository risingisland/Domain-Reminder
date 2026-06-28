<?php
/**
 * includes/mailer.php
 *
 * Unified mailer helper. Reads mail_method from adm_settings and sends
 * via PHP mail() or PHPMailer SMTP accordingly.
 *
 * Usage:
 *   require_once('includes/mailer.php');
 *   $result = send_mail($to, $subject, $body);
 *   if ($result !== true) { echo "Failed: $result"; }
 */

/**
 * @return bool|string Returns true on success, or an error message string on failure.
 */
function send_mail(string $to, string $subject, string $body) {
    global $pdo;

    // Load mail settings from DB
    $stmt = $pdo->query(
        "SELECT mail_method, smtp_host, smtp_port, smtp_encryption,
                smtp_user, smtp_pass, smtp_from_name, smtp_from_email,
                adminEmail
         FROM adm_settings WHERE id = 1"
    );
    $cfg = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$cfg) {
        return 'Could not load mail settings from database.';
    }

    $method     = $cfg['mail_method'] ?? 'mail';
    $from_name  = $cfg['smtp_from_name']  ?: 'Domain Reminder';
    $from_email = $cfg['smtp_from_email'] ?: ($cfg['adminEmail'] ?: 'noreply@localhost');

    // ----------------------------------------------------------------
    // PHP mail()
    // ----------------------------------------------------------------
    if ($method === 'mail') {
        $headers  = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=utf-8\r\n";
        $headers .= "From: " . $from_name . " <" . $from_email . ">\r\n";
        $ok = mail($to, $subject, $body, $headers);
        return $ok ? true : 'mail() returned false. Check your server mail configuration.';
    }

    // ----------------------------------------------------------------
    // PHPMailer SMTP
    // ----------------------------------------------------------------
    if ($method === 'smtp') {
        if (!file_exists(__DIR__ . '/../phpmailer/PHPMailer.php')) {
            return 'PHPMailer not found. Expected at /phpmailer/PHPMailer.php';
        }

        require_once __DIR__ . '/../phpmailer/Exception.php';
        require_once __DIR__ . '/../phpmailer/PHPMailer.php';
        require_once __DIR__ . '/../phpmailer/SMTP.php';

        $mail = new PHPMailer\PHPMailer\PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host        = $cfg['smtp_host'];
            $mail->Port        = (int)($cfg['smtp_port'] ?: 587);
            $mail->SMTPAuth    = !empty($cfg['smtp_user']);
            $mail->Username    = $cfg['smtp_user'];
            $mail->Password    = smtp_pass_decrypt($cfg['smtp_pass']);
            switch ($cfg['smtp_encryption']) {
                case 'ssl':  $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;    break;
                case 'tls':  $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS; break;
                default:     $mail->SMTPSecure = ''; break;
            }

            $mail->setFrom($from_email, $from_name);
            $mail->addAddress($to);
            $mail->isHTML(true);
            $mail->CharSet  = 'UTF-8';
            $mail->Subject  = $subject;
            $mail->Body     = $body;

            $mail->send();
            return true;

        } catch (PHPMailer\PHPMailer\Exception $e) {
            return $mail->ErrorInfo;
        }
    }

    return 'Unknown mail method: ' . htmlspecialchars($method);
}

// ----------------------------------------------------------------
// Simple reversible encryption for storing SMTP password in SQLite.
// Not military-grade, but prevents plain-text storage at rest.
// Key is derived from the DB file path so it's installation-specific.
// ----------------------------------------------------------------
function smtp_pass_key(): string {
    return hash('sha256', __DIR__ . '/dbconnect.php');
}

function smtp_pass_encrypt(string $plain): string {
    if ($plain === '') return '';
    $key    = smtp_pass_key();
    $iv     = openssl_random_pseudo_bytes(16);
    $enc    = openssl_encrypt($plain, 'AES-256-CBC', $key, 0, $iv);
    return base64_encode($iv . '::' . $enc);
}

function smtp_pass_decrypt(string $stored): string {
    if ($stored === '') return '';
    $decoded = base64_decode($stored);
    if (strpos($decoded, '::') === false) {
        // Legacy plain-text fallback
        return $stored;
    }
    [$iv, $enc] = explode('::', $decoded, 2);
    $key = smtp_pass_key();
    $plain = openssl_decrypt($enc, 'AES-256-CBC', $key, 0, $iv);
    return $plain !== false ? $plain : '';
}
