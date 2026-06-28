# Domain Reminder

A self-hosted domain management tool for tracking domain registrations, renewal dates, and client associations. Built with PHP and SQLite — no database server required.

![PHP](https://img.shields.io/badge/PHP-7.4%2B-blue) ![SQLite](https://img.shields.io/badge/Database-SQLite-green) ![License](https://img.shields.io/badge/License-MIT-yellow)

<img width="1920" alt="image" src="https://github.com/user-attachments/assets/6b4aedf5-4bba-46f1-8209-699909dbc259" />

---

## Features

- **Domain tracking** — store registrar, registration date, renewal date, renew link, and WHOIS data per domain
- **Client management** — associate domains with clients, including company, contact details, and notes
- **Expiry dashboard** — colour-coded expiring domains (30 / 60 / 90 day bands) with a calendar view
- **WHOIS lookup** — automatic WHOIS data retrieval using [io-developer/php-whois](https://github.com/io-developer/php-whois)
- **Email notifications** — cron-triggered expiry notices via PHP `mail()` or SMTP (PHPMailer)
- **Encrypted credential storage** — per-domain login credentials stored with XOR + base64 encoding
- **Backup & restore** — one-click SQLite database backup download and restore upload
- **Multilingual** — English, Spanish, Polish; new languages added by dropping a file in `langs/`
- **Remember Me** — persistent login with secure token rotation
- **PHP 7.4–8.x compatible**

---

## Requirements

- PHP 7.4 or higher (tested up to 8.3)
- PHP extensions: `pdo_sqlite`, `openssl`, `mbstring`
- Web server: Apache or Nginx
- No MySQL or database server needed

---

## Installation

1. **Upload** all files to your web server
2. **Set permissions** — `config/` and `backups/` must be writable (`chmod 755` or `777`)
3. **Visit** `https://yourdomain.com/install.php` in your browser
4. **Delete `install.php`** after installation completes
5. **Log in** with the default credentials:
   - Username: `admin`
   - Password: `pass`
6. **Change your password** immediately in Settings

---

## Directory Structure

```
/
├── assets/                 CSS, JS, images (AdminLTE, Font Awesome, jQuery)
├── backups/                SQLite backups and CSV exports (writable)
├── config/                 database.sqlite + version.php (writable)
├── includes/               Core PHP includes
│   ├── dbconnect.php       PDO SQLite connection
│   ├── functions.php       Helper functions
│   ├── languages.php       Language loader
│   ├── lang-menu.php       Dynamic language dropdown builder
│   └── mailer.php          Unified mail() / SMTP helper
├── langs/                  Translation files
│   ├── lang.en.php         English
│   ├── lang.es.php         Spanish
│   └── lang.pl.php         Polish
├── phpmailer/              PHPMailer library (Exception.php, PHPMailer.php, SMTP.php)
├── phpwhois/               io-developer/php-whois library
├── dashboard.php           Main dashboard with expiry table and calendar
├── domains.php             Domain list
├── domains-edit.php        Add / edit domain, WHOIS lookup, credential storage
├── domains-expiring.php    Colour-coded expiry view with cron trigger button
├── clients.php             Client list
├── clients-add.php         Add / edit client with associated domains
├── backup.php              Backup and restore UI
├── backup-go.php           Backup download handler
├── cron.php                Email notification endpoint (token-secured)
├── settings.php            Admin settings: account, email/SMTP, language, cron token
├── index.php               Login page with Remember Me
├── logout.php              Session and cookie cleanup
├── install.php             First-run installer (delete after use)
├── -install-default-admin.php   Reset admin account (delete after use)
└── -install-demo-data.php       Load demo data (delete after use)
```

---

## Adding a Language

1. Copy `langs/lang.en.php` to `langs/lang.xx.php` (replace `xx` with the language code)
2. Translate all values in the new file
3. The language will automatically appear in the Settings dropdown — no code changes needed

---

## SMTP Email Setup

1. Go to **Settings → Email**
2. Select **SMTP** as the mail method
3. Enter your SMTP host, port, encryption, username, and password
4. Use the **Test SMTP** button to verify before saving
5. SMTP passwords are stored AES-256-CBC encrypted

---

## Cron Notifications

The expiry notification email can be triggered:

- **Manually** — from the Expiring Soon page via the Send Notice button
- **Automatically** — by scheduling a server cron job:

```
0 8 * * * curl -s "https://yourdomain.com/cron.php?cron=do&d=45&token=YOUR_TOKEN" > /dev/null
```

The token is found in **Settings → Admin → Cron Token**. Without the correct token, the endpoint returns a 403 and reveals nothing.

To regenerate the token (e.g. if compromised), click the refresh icon next to the token in Settings. Update any existing cron jobs with the new token.

---

## Backup & Restore

**Backup** — Settings → Databases → Backup Now. Downloads a copy of `config/database.sqlite`.

**Restore** — Upload a `.sqlite` backup file via the restore form on the same page.

---

## Security Notes

- Delete `install.php`, `-install-default-admin.php`, and `-install-demo-data.php` after use — the dashboard warns you if these files are still present
- The cron notification URL is protected by a secret token; without it the page returns a 403
- SMTP passwords are stored encrypted (AES-256-CBC)
- Domain credentials (FTP, database logins etc.) are stored XOR-encrypted
- All database queries use PDO prepared statements
- ORDER BY clauses use column whitelists to prevent SQL injection

---

## Credits

Built on [AdminLTE](https://adminlte.io/) by Abdullah Almsaeed.
WHOIS lookups by [io-developer/php-whois](https://github.com/io-developer/php-whois).
Email via [PHPMailer](https://github.com/PHPMailer/PHPMailer).
