<?php

    require_once("dbconnect.php");
    $stmt = $pdo->query("SELECT id, adminLang FROM adm_settings WHERE id = 1");
    $row = $stmt->fetch();
    if ($row) {
        foreach ($row as $key => $value) { $$key = $value; }
    }

    header('Cache-control: private'); // IE 6 FIX

    if (isset($_GET['lang'])) {
        $lang = $_GET['lang'];
        $_SESSION['lang'] = $lang;
        setcookie('lang', $lang, time() + (3600 * 24 * 30));
    } elseif (isset($_SESSION['lang'])) {
        $lang = $_SESSION['lang'];
    } elseif (isset($_COOKIE['lang'])) {
        $lang = $_COOKIE['lang'];
    } else {
        $lang = $adminLang ?? 'en';
    }

    switch ($lang) {
        case 'en': $lang_file = 'lang.en.php'; break;
        case 'es': $lang_file = 'lang.es.php'; break;
        case 'pl': $lang_file = 'lang.pl.php'; break;
        /* Add new lang here... */
        default:   $lang_file = 'lang.' . ($adminLang ?? 'en') . '.php';
    }

    include_once 'langs/' . $lang_file;
