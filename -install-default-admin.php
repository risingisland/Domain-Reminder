<?php
/**
 * -install-default-admin.php
 * Resets the adm_settings table and restores the default admin account.
 * DELETE THIS FILE after use.
 */

$db_path = __DIR__ . '/config/database.sqlite';

try {
    $pdo = new PDO('sqlite:' . $db_path);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $pdo->exec("DROP TABLE IF EXISTS adm_settings");

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
        smtp_from_email VARCHAR(255) NOT NULL DEFAULT '',
        cron_token VARCHAR(64) NOT NULL DEFAULT ''
    )");

    $cron_tok = bin2hex(random_bytes(32));
    $stmt = $pdo->prepare("INSERT INTO adm_settings (id, username, password, remember_token, adminEmail, adminLang, show_debug, show_domdata, mail_method, smtp_port, smtp_encryption, cron_token) VALUES (1, 'admin', '1a1dc91c907325c69271ddf0c944bc72', '', 'demo@email.com', 'en', 1, 1, 'mail', 587, 'tls', ?)");
    $stmt->execute([$cron_tok]);

    echo "Admin Reset. Delete this file now.";

} catch (PDOException $e) {
    echo "Error: " . htmlspecialchars($e->getMessage());
}
