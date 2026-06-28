<?php
/**
 * -install-demo-data.php
 * Resets all tables and inserts demo data.
 * DELETE THIS FILE after use.
 */

$db_path = __DIR__ . '/config/database.sqlite';

try {
    $pdo = new PDO('sqlite:' . $db_path);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec('PRAGMA foreign_keys=OFF');

    // Drop and recreate all tables
    $pdo->exec("DROP TABLE IF EXISTS adm_clients");
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

    $pdo->exec("DROP TABLE IF EXISTS adm_data");
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

    $pdo->exec("DROP TABLE IF EXISTS adm_domains");
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

    // Demo clients
    $clients = [
        [1, 'Sergey Brin', 'Co-founder of Google.', 'Alphabet Inc.', 'Co-Founder', 'sergey@gmail.com', 'https://abc.xyz/', '+1 (650) 253-0000', 'Mountain View, California', '2026-02-25 10:23:38'],
        [2, 'Jeff Bezos', 'Founder of Amazon.', 'Amazon.com, Inc.', 'CEO', 'jeff@amazon.com', 'Amazon.com', '1-206-266-1000', 'Seattle, Washington', '2026-02-25 10:28:19'],
        [3, 'Mark Zuckerberg', 'Co-founder of Facebook.', 'Meta Platforms', 'Co-Founder', 'mark@facebook.com', 'Facebook.com', '650-543-4800', 'Menlo Park, California', '2026-02-25 10:32:01'],
        [4, 'Tim Cook', 'CEO of Apple.', 'Apple Inc.', 'Chief Executive Officer', 'tim@apple.com', 'Apple.com', '(408) 996-1010', 'Cupertino, California', '2026-02-25 10:35:20'],
        [5, 'Bill Gates', 'Co-founder of Microsoft.', 'Microsoft Corporation', 'Co-Founder', 'bill.gates@microsoft.com', 'https://www.gatesnotes.com/', '(206) 709-3400', 'Redmond, Washington', '2026-02-25 10:39:25'],
    ];
    $stmt = $pdo->prepare("INSERT INTO adm_clients (id, name, comment, company, job_title, email, web, phone, address, dateCreated) VALUES (?,?,?,?,?,?,?,?,?,?)");
    foreach ($clients as $c) { $stmt->execute($c); }

    // Demo data
    $data = [
        [1, 4, 'LwAPHgg=', 'BH9aV0dZ', 'fG0IDlIAHU8HGEJDGwVQFR0qTQ==', 'ACAgICAgICAgACAgIA==', '', ''],
        [2, 5, 'NRkdDw==', 'UjMfHxgJQQwfHg==', 'fWEYERoADgIDARRAKRVQHw1HDRZNQFs4A14=', 'ACAgICAAICAg', '', ''],
        [3, 2, 'KwARDhg=', 'QycSEFRQXA==', 'ZCNBCAsNGE8KQg9PFFxGNEEQAAEdCh9LEEE=', 'ACAgICAgICAAICAg', 'XykCAgkKABwf', 'SikUEQwMCQA='],
        [4, 1, 'KwgHHA0=', 'QiAeEg0AHEo=', 'e2EFAAwKTk8PA01GWiRNDBUBB0sQQQ==', 'ACAgICAgACAgIA==', '', ''],
        [5, 3, 'NAodAAkcTA==', 'QSYcHFU=', 'eGcEAQgbRVkLDhkRSCgaTxAJF0tDHAhQQyQHBgkLRUgMHUM=', 'ACAgICAgACAgIA==', '', ''],
    ];
    $stmt = $pdo->prepare("INSERT INTO adm_data (id, type, aa, bb, cc, dd, ee, ff) VALUES (?,?,?,?,?,?,?,?)");
    foreach ($data as $d) { $stmt->execute($d); }

    // Demo domains
    $domains = [
        [1, 'MarkMonitor Inc.', 'Google.com', 'Google LLC search engine.', 1, '', 'http://www.markmonitor.com', '2021-09-13', '2026-02-25 10:24:10', '1997-09-15'],
        [2, 'MarkMonitor Inc.', 'Amazon.com', 'Amazon e-commerce.', 2, '', 'http://www.markmonitor.com', '2021-10-30', '2026-02-25 10:28:37', '1994-11-01'],
        [3, 'RegistrarSafe, LLC', 'Facebook.com', 'Meta Platforms social network.', 3, '', 'https://www.registrarsafe.com', '2026-03-30', '2026-02-25 10:32:15', '1997-03-29'],
        [4, 'CSC Corporate Domains, Inc.', 'Apple.com', 'Apple Inc. tech company.', 4, '', 'https://www.cscprotectsbrands.com', '2026-02-20', '2026-02-25 10:35:57', '1987-02-19'],
        [5, 'MarkMonitor Inc.', 'Microsoft.com', 'Microsoft Corporation.', 5, '', 'http://www.markmonitor.com', '2026-05-02', '2026-02-25 10:39:53', '1991-05-02'],
    ];
    $stmt = $pdo->prepare("INSERT INTO adm_domains (id, registrar, domain, comment, clientID, whoisreply, renew_link, renewalDate, dateCreated, registrationDate) VALUES (?,?,?,?,?,?,?,?,?,?)");
    foreach ($domains as $d) { $stmt->execute($d); }

    // Default admin
    $cron_tok = bin2hex(random_bytes(32));
    $pdo->prepare("INSERT INTO adm_settings (id, username, password, remember_token, adminEmail, adminLang, show_debug, show_domdata, mail_method, smtp_port, smtp_encryption, cron_token) VALUES (1, 'admin', '1a1dc91c907325c69271ddf0c944bc72', '', 'demo@email.com', 'en', 1, 1, 'mail', 587, 'tls', ?);")->execute([$cron_tok]);

    echo "Demo Database Restored. Delete this file now.";

} catch (PDOException $e) {
    echo "Error: " . htmlspecialchars($e->getMessage());
}
