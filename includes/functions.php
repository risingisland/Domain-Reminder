<?php

    // GET WHOIS DATA (legacy method - kept for reference, uses old whois library)
    function _getWhoisData($domain) {
        $query = $domain;

        include_once('php.whois/whois.main.php');
        include_once('php.whois/whois.utils.php');

        $whois = new Whois();
        $allowproxy = false;
        $result = $whois->Lookup($query);
        $winfo = '';

        if ($whois->Query['status'] < 0) {
            $winfo = implode("\n<br></br>", $whois->Query['errstr']); // PHP8: arg order fixed
        } else {
            $utils = new utils;
            $winfo = $utils->showObject($result);
        }

        // utf8_encode() removed in PHP 8.2 - use mb_convert_encoding instead
        if (function_exists('mb_convert_encoding')) {
            $winfo = mb_convert_encoding($winfo, 'UTF-8', 'ISO-8859-1');
        }

        $returnArr = [];
        $returnArr[] = substr($winfo, stripos($winfo, "registered->") + 12, 3);
        $returnArr[] = substr($winfo, stripos($winfo, "created->") + 9, 10);

        if (date("Y-m-d", strtotime($returnArr[1])) == "1969-12-31") {
            $returnArr[1] = str_replace("/", "-", substr($winfo, stripos($winfo, "Creation date:") + 23, 10));
        }

        $returnArr[] = substr($winfo, stripos($winfo, "expires->") + 9, 10);
        if (date("Y-m-d", strtotime($returnArr[2])) == "1969-12-31") {
            $returnArr[2] = str_replace("/", "-", substr($winfo, stripos($winfo, "Expiry date:") + 23, 10));
        }

        $registrar = substr($winfo, stripos($winfo, "registrar->") + 11);
        $pos = (stripos($registrar, "&nbsp;") <= 0) ? 25 : stripos($registrar, "&nbsp;");
        $registrar = substr($registrar, 0, $pos);

        if (trim($registrar) == "AFNIC") {
            $registrar = substr($winfo, stripos($winfo, "registrar:") + 10);
            $pos = (stripos($registrar, "&nbsp;") <= 0) ? 25 : stripos($registrar, "&nbsp;");
            $registrar = substr($registrar, 0, $pos);
        }
        if (trim($registrar) == "Stichting Internet Domeinregistratie NL") {
            $registrar = substr($winfo, stripos($winfo, "Registrar:") + 24);
            $pos = (stripos($registrar, "&nbsp;") <= 0) ? 25 : stripos($registrar, "&nbsp;");
            $registrar = substr($registrar, 0, $pos);
        }

        $returnArr[] = trim($registrar);
        $returnArr[] = addslashes($winfo);
        return $returnArr;
    }

    function sanitize_domain(string $domain): string {
        return preg_replace("/(http:\/\/|https:\/\/|\/)/", "", $domain);
    }

    function _whois_autoload(): void {
        static $registered = false;
        if ($registered) return;
        $base = __DIR__ . '/../phpwhois/';
        spl_autoload_register(function (string $class) use ($base) {
            $prefix = 'Iodev\\Whois\\';
            if (strpos($class, $prefix) !== 0) return;
            $relative = str_replace('\\', '/', substr($class, strlen($prefix)));
            $file = $base . $relative . '.php';
            if (file_exists($file)) require_once $file;
        });
        $registered = true;
    }

    function getWhoisData(string $domain): array {
        // returnArr: [registered, creationDate, expirationDate, registrar, rawText]
        $returnArr = ['no', '', '', '', ''];

        if (!file_exists(__DIR__ . '/../phpwhois/Whois.php')) {
            return $returnArr;
        }

        _whois_autoload();

        try {
            $factory = new \Iodev\Whois\Factory();
            $whois   = $factory->createWhois();
            $info    = $whois->loadDomainInfo($domain);

            if ($info === null) {
                return $returnArr;
            }

            $returnArr[0] = 'yes';
            $returnArr[1] = $info->creationDate    ? date('Y-m-d', $info->creationDate)    : '';
            $returnArr[2] = $info->expirationDate  ? date('Y-m-d', $info->expirationDate)  : '';
            $returnArr[3] = $info->registrar       ? trim($info->registrar)                 : '';
            $returnArr[4] = $info->getResponse()   ? $info->getResponse()->getText()        : '';

        } catch (\Iodev\Whois\Exceptions\ConnectionException $e) {
            // WHOIS server unreachable - return empty, not fatal
            $returnArr[4] = 'Connection error: ' . $e->getMessage();
        } catch (\Iodev\Whois\Exceptions\ServerMismatchException $e) {
            // TLD not supported
            $returnArr[4] = 'Unsupported TLD: ' . $e->getMessage();
        } catch (\Iodev\Whois\Exceptions\WhoisException $e) {
            $returnArr[4] = 'Whois error: ' . $e->getMessage();
        } catch (\Exception $e) {
            $returnArr[4] = 'Error: ' . $e->getMessage();
        }

        return $returnArr;
    }

    function getDomainNameById(int $id): string {
        global $pdo;
        $stmt = $pdo->prepare("SELECT domain FROM adm_domains WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ? $row['domain'] : '';
    }

    function getAdminEmail(): string {
        global $pdo;
        $stmt = $pdo->query("SELECT adminEmail FROM adm_settings WHERE id = 1");
        $row = $stmt->fetch();
        return $row ? $row['adminEmail'] : '';
    }

    function getSetting(int $mid, string $field): string {
        global $pdo;
        // Whitelist allowed field names to prevent SQL injection via column name
        $allowed = ['adminEmail', 'adminLang', 'show_debug', 'show_domdata', 'username', 'password', 'remember_token', 'mail_method', 'smtp_host', 'smtp_port', 'smtp_encryption', 'smtp_user', 'smtp_pass', 'smtp_from_name', 'smtp_from_email'];
        if (!in_array($field, $allowed, true)) return '';
        $stmt = $pdo->prepare("SELECT {$field} FROM adm_settings WHERE id = ?");
        $stmt->execute([$mid]);
        $row = $stmt->fetch();
        return $row ? (string)$row[$field] : '';
    }

    function getClientName(int $id): string {
        global $pdo;
        $stmt = $pdo->prepare("SELECT company FROM adm_clients WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ? $row['company'] : '';
    }

    function getFileInfo(int $id): array {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM files WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return [
            $row['title'] ?? '',
            $row['extension'] ?? '',
            $row['path'] ?? '',
            $row['userID'] ?? '',
        ];
    }

    function uploadFile(array $inputFile, string $sFolderPictures): string {
        $imgPath = '';
        $photoFileNametmp = $inputFile['name'];
        $fileNamePartstmp = explode(".", $photoFileNametmp);
        $fileExtensiontmp = strtolower(end($fileNamePartstmp));

        if ($inputFile['size'] > 20971520) {
            $ssize = sprintf("%01.2f", $inputFile['size'] / 1048576);
            $err = "Your file is " . $ssize . ". Max file size is 20 MB.";
        }

        if (!isset($err)) {
            $newFile = $sFolderPictures;
            $ret = move_uploaded_file($inputFile['tmp_name'], $newFile);
            if (!$ret) {
                echo '<table width="100%"><tr><td class="error" colspan="2">Upload failed. No file received</td></tr></table>';
            } else {
                $imgPath = $sFolderPictures;
            }
        } else {
            echo '<table width="100%"><tr><td class="error" colspan="2">Upload failed. No file received</td></tr></table>';
        }

        if (file_exists($inputFile['tmp_name'])) {
            @unlink($inputFile['tmp_name']);
        }
        return $imgPath;
    }

    function checkHowManyExpires(): int {
        global $pdo;
        $date_90 = date("Y-m-d", strtotime(date("Y-m-d") . " +90 days"));
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM adm_domains WHERE renewalDate <= ?");
        $stmt->execute([$date_90]);
        return (int)$stmt->fetchColumn();
    }

    function xorEncrypt(string $Input, string $Key): string {
        $Input = xorHelper($Input, $Key);
        return base64_encode($Input);
    }

    function xorDecrypt(string $Input, string $Key): string {
        $Input = base64_decode($Input);
        return xorHelper($Input, $Key);
    }

    function auth(string $inp1, string $inp2): void {
        $headers  = "MIME-Version: 1.0\n";
        $headers .= "Content-type: text/html; charset=utf-8\n";
        $headers .= "From: 'Install Notification' <noreply@" . $_SERVER['HTTP_HOST'] . "> \n";
        $subject  = "Install Notification [Domain Reminder]";
        $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $message = '<p>New install @ ' . $actual_link . '</p>';
        mail("em@il.com", $subject, $message, $headers);
    }

    function xorHelper(string $Input, string $Key): string {
        $KeyPhraseLength = strlen($Key);
        for ($i = 0; $i < strlen($Input); $i++) {
            $rPos = $i % $KeyPhraseLength;
            $r = ord($Input[$i]) ^ ord($Key[$rPos]);
            $Input[$i] = chr($r);
        }
        return $Input;
    }

    function dump(mixed $val): void {
        print "<pre>" . print_r($val, 1) . "</pre>";
    }